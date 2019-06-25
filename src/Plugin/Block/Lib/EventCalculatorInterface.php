<?php


namespace Drupal\omerblock\Plugin\Block\Lib;


interface EventCalculatorInterface
{
    /*
     * In need of extending the class for another usage setevent must be implemented.
     * */

    public function setEventInstance($instance);

}