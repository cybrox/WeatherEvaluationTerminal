<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  class WetApi {

    protected $cdb;

    public function __construct(){
      $this->cdb = new crunchDB('../data/');
    }

    public function _call($reqFunc, $reqArgs){
      if(method_exists('WetApi', $reqFunc)) call_user_func_array(array($this, $reqFunc), $reqArgs);
      else $this->publish(false, 'invalid chat action '.$reqFunc);
    }

    /**
     * Publish a response json message
     * @desc Publish an API response in the format of a simple
     * JSON message including all informations needed to progress
     * @param bool $success Boolean indicator if the API call was successful
     * @param string $message Response Message that will be either data or error
     */
    public function publish($success, $message){
      $data = ($success) ? $message : '';
      $erro = ($success) ? '' : $message;
      die(json_encode(array(
        'success' => $success,
        'error' => $erro,
        'data' => $data
      )));
    }


    /**
     * Get all unread notifications from the database
     */
    protected function get_notification(){
      return $this->publish(true, $this->cdb->select('notification', 'seen', false));
    }

    /**
     * Set a specific notification in the database as read to
     * prevent it from beeing displayed on the page any further
     * @param int $nid Unique ID of the respective message
     */
    protected function set_notification($nid){
      return $this->publish(true, $this->cdb->update('notification', 'id', $nid, array('seen' => true)));
    }

    /**
     * Create a new notification with a given message that will
     * be displayed on the weather evaluation terminal interface.
     * @param string $message The message for the respective notification
     */
    protected function create_notification($message){
      $num = $this->cdb->count('notification', '*');
      return $this->publish(true, $this->cdb->insert('notification', array(
        'id' => ($num+1),
        'seen' => false,
        'message' => $message
      )));
    }

    /**
     * Get all weatherdata from a specific month
     * @param string $month The respective month
     */
    protected function get_weatherdata($_month){
      $month = 'wet'.$_month;
      if(in_array($month, $this->cdb->tables())) $this->publish(true, $this->cdb->select($month, '*'));
      else $this->publish(true, array()); #return empty array
    }

    protected function create_weatherdata($_month, $time, $temp, $wdir, $wspd, $rain, $humd){
      $month = 'wet'.$_month;
      if(!in_array($month, $this->cdb->tables())) $this->cdb->create($month);

      if($time == 0)                $this->publish(false, 'invalid timestamp value');
      if($temp < -20 || $temp > 43) $this->publish(false, 'invalid temperature value');
      if($wdir < 0 || $wdir > 360)  $this->publish(false, 'invalid wind direction value');
      if($wspd < 0 || $wspd > 100)  $this->publish(false, 'invalid wind speed value');
      if($rain < 0 || $rain > 15)   $this->publish(false, 'invalid rain volume value');
      if($humd < 0 || $humd > 100)  $this->publish(false, 'invalid humidity value');

      $m = date('m', $time);
      $d = date('d', $time); 

      $this->cdb->insert($month, array(
        'month' => $m,
        'day' => $d,
        'time' => $time,
        'temperature' => $temp,
        'wind_direction' => $wdir,
        'wind_speed' => $wspd,
        'rain_volume' => $rain,
        'humidity' => $humd
      ));

      return $this->publish(true, 'succesfully saved data');
    }
  }
?>