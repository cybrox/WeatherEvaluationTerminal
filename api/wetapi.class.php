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
      else $this->publish(false, 'invalid api action '.$reqFunc);
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
      return $this->publish(true, $this->cdb->update('notification', 'uid', $nid, array('seen' => true)));
    }

    /**
     * Create a new notification with a given message that will
     * be displayed on the weather evaluation terminal interface.
     * @param string $message The message for the respective notification
     */
    protected function create_notification($message){
      $num = $this->cdb->count('notification', '*');
      return $this->publish(true, $this->cdb->insert('notification', array(
        'uid' => ($num+1),
        'seen' => false,
        'date' => date('d.m.Y, h:m', time()),
        'message' => $message
      )));
    }

    /**
     * Special type of notification, generate a weather error
     * notification with a german string
     */
    private function weather_notification($value, $actual, $min, $max){
      $this->create_notification("Der gemessene '".$value."' Wert (".$actual.") liegt nicht im erlaubten Bereich von ".$min." - ".$max."");
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

    protected function create_weatherdata($time=0, $temp=50, $wdir=-1, $wspd=-1, $rain=-1, $humd=-1){
      if(intval($time) < 100) $this->publish(false, 'invalid timestamp value');

      $y = date('Y', $time);
      $m = date('m', $time);
      $d = date('d', $time); 
      $month = 'wet'.$y.'-'.$m;
      if(func_num_args() != 6) $this->publish(false, 'not enough [or] too many arguments given');
      if(!in_array($month, $this->cdb->tables())) $this->cdb->create($month);

      if($temp < -20 || $temp > 43) $this->weather_notification('Temperatur', $temp, -20, 43);
      if($wdir < 0 || $wdir > 360)  $this->weather_notification('Windrichtung', $wdir, 0, 360);
      if($wspd < 0 || $wspd > 100)  $this->weather_notification('Windgeschwindigkeit', $wspd, 0, 100);
      if($rain < 0 || $rain > 15)   $this->weather_notification('Regenmenge', $rain, 0, 15);
      if($humd < 0 || $humd > 100)  $this->weather_notification('Luftfeuchtigkeit', $humd, 0, 100);

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