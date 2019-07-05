<?php
namespace Drupal\omerblock\Plugin\Block\Lib;

use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\node\NodeInterface;

class EventTimeCalculator
{

    // Since drupal supports >= 5.6 cant declare return types //

    private $node_instance;
    private $current_day;
    private $event_day;



    public function getEventStatus(){

      if($this->current_day > $this->event_day){

          return 'This event already passed';
      }

      if($this->current_day == $this->event_day){

          return 'This event is happening today';
      }

      if($this->current_day < $this->event_day){

          $diff = strtotime($this->node_instance->field_event_date->value) - \Drupal::time()->getCurrentTime();

          $days_left = floor( $diff / ( 60 * 60 * 24 ) ); //seconds/minute*minutes/hour*hours/day)

          return $days_left . ' days left until event starts';

      }

        //return \Drupal::service('date.formatter')->format($this->node_instance->field_event_date->getTimeStamp(), 'custom', 'd-m-Y');

    }


    /**
     * EventTimeCalculator constructor.
     * @param NodeInterface $instance
     * @throws EntityMalformedException
     */
    public function __construct(NodeInterface $instance){

        if(!$instance instanceof NodeInterface){
            \Drupal::logger('EventTimeCalculator')->error('Instance of event time calculator,  must be an instance of Drupal');
            throw new EntityMalformedException('Instance of event time calculator,  must be an instance of Drupal');

        }

        $this->node_instance = $instance;

        $this->event_day= date('m-d-Y', strtotime($this->node_instance->field_event_date->value));

        // dont know if this is related on user locale //
        $this->current_day = \Drupal::service('date.formatter')->format(\Drupal::time()->getCurrentTime(), 'custom', 'm-d-Y');


    }

}