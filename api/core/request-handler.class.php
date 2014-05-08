<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  class RequestHandler {

    private $reqAction;
    private $reqParams;
    private $reqScope;
    protected $responseIs;
    protected $notHandler;
    protected $wetHandler;

    /**
     * @method __construct
     * @name Class Constructor
     * @desc Check action and scope and call subclass
     * @param {array} requestArray - requested parameters
     */
    public function __construct($requestArray){
      $this->reqAction = strtolower((!empty($requestArray[0])) ? $requestArray[0] : "");
      $this->reqScope  = strtolower((!empty($requestArray[1])) ? $requestArray[1] : "");
      $this->reqParams = $requestArray;

      if(!in_array($this->reqAction, array("read", "write")))
        return $this->publish(false, "invalid api action given");

      $this->responseIs = [false, "invalid api action or scope"];

      if($this->reqScope == "notifications"){
        $this->notHandler = new NotificationHandler();
        if($this->reqAction == "read")  $this->responseIs = $this->notHandler->readNotification();
        if($this->reqAction == "write") $this->responseIs = $this->notHandler->markNotification($this->reqParams);
      }
      if($this->reqScope == "weatherdata"){
        $this->wetHandler = new WeatherdataHandler();
        if($this->reqAction == "read") $this->responseIs = $this->wetHandler->readWeatherdata($this->reqParams);
      } 

      if (empty($this->responseIs[0]))   return $this->publish(true, $this->responseIs);
      if ($this->responseIs[0] == false) return $this->publish(false, $this->responseIs[1]);
      else return $this->publish(true, $this->responseIs);
    }


    /**
     * @method publish
     * @name Publish Message
     * @desc Publish the response in JSON format
     * @param {bool} success - define if the request was successful
     * @param {string} message - the error message or data string / array
     */
    protected function publish($success, $message){
      $data  = ($success) ? $message : "";
      $error = ($success) ? "" : $message;

      print json_encode(array(
        "status" => $success,
        "data"   => $data,
        "error"  => $error
      ));
    }
  }

?>