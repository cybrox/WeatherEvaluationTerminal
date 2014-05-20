<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  class NotificationHandler {
    private $db;

    public function __construct(){
      $this->db = new DatabaseHandler();
    }

    public function readNotification(){
      return $this->db->findRead('notifications', 'seen', false);
    }

    public function writeNotification($message){
      $r = '';
      $c = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      for ($i = 0; $i < $length; $i++) $r .= $c[rand(0, strlen($c) - 1)];
      return $this->db->writeFile('notifications', array(
        "id" => $r,
        "seen" => false,
        "message" => $message
      ));
    }

    public function markNotification($params){
      if(!preg_match('#[0-9]{1,}#', $params[2])) return array(false, 'invalid notification id');
      else return $this->db->findWrite('notifications', 'id', $params[2], 'seen', true);
    }

  }

?>