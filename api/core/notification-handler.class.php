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

    public function writeNotification($params){
      if(!preg_match('#[0-9]{1,}#', $params[2])) return array(false, 'invalid notification id');
      else return $this->db->findWrite('notifications', 'id', $params[2], 'seen', true);
    }

  }

?>