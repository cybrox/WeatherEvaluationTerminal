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

  }

?>