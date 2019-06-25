<?php
namespace Drupal\omerblock\Plugin\Block\Lib;

use Drupal\Core\Entity\EntityMalformedException;
use Drupal\omerblock\Plugin\Block\Lib\EventCalculatorInterface;


class EventTimeCalculator implements EventCalculatorInterface
{

    // Since drupal supports >= 5.6 cant declare return types //

    public $instance;
    public $error;
    public $current_time;
    public $event_date;



    // key item relation in result might give you flexibilty to add new params in need //
    protected $result = [
        'days' => null,
        'hours' => null,
    ];


    public function HowManyDaysLeft(){

        $this->setDateField();

        if(!$this->isSetFieldDate()){
           throw new EntityMalformedException('Field date is just empty :(. Please set the field you desired by calling setDateField()');
        }

        $this->setCurrentTime();
        $this->setResult();

        return $this->result;

    }


    public function setEventInstance($instance){

        if(!$instance instanceof \Drupal\node\NodeInterface){
            \Drupal::logger('EventTimeCalculator')->error('Instance of event time calculator,  must be an instance of Drupal');
             throw new EntityMalformedException('Instance of event time calculator,  must be an instance of Drupal');

        }
        $this->instance = $instance;

    }

    public function getInstance(){

        return $this->instance;

    }

    public function isSetFieldDate(){

        return $this->getDateField();

    }

    protected function setDateField($date = '') {

        if(empty($date)){
        return $this->event_date = strtotime($this->instance->field_event_date->value);
        }
        return $this->event_date = strtotime($date);

    }

    /**
     * @return mixed
     */
    public function getDateField(){

        return $this->event_date;

    }

    public function setCurrentTime(){
       // Set Drupal current time //
        $this->current_time = \Drupal::time()->getCurrentTime();

    }

    public function setResult(){
        // Find difference in event date and current time in form of seconds //
        $diff = $this->event_date - $this->current_time;

        // Get Days //
        $this->result['days'] = floor( $diff / ( 60 * 60 * 24 ) ); //seconds/minute*minutes/hour*hours/day)

        // Get Hours //
        $this->result['hours'] = round(( $diff - $this->result['days'] * 60 * 60 * 24 ) / ( 60 * 60 ) );

       // formula might be assigned into properties but would be just funny that much //
       // void method, in future can be changed as long as result returned, no problem at all. //
    }

    public function printResult(){
        // this is for at least some visuality. does not go into themes. yes its hardcoded :)  //

        $this->HowManyDaysLeft();


        if($this->result['days'] < 0){

            return 'This event already passed';

        }

        if($this->result['days'] >= 1){

            return $this->result['days'] .' days ' . $this->result['hours'] . ' hours left';

        }

        if($this->result['days'] == 0 AND $this->result['hours'] > 0){

            return 'This event is happening today in ' . $this->result['hours'] . ' hours';

        }

    }

}