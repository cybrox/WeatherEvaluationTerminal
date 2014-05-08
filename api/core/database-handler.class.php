<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  class DatabaseHandler {
    
    protected function stream($filename){
      return '../data/'.$filename.'.json';
    }

    protected function isStream($filename){
      return file_exists($this->stream($filename));
    }

    public function readStream($filename){
      if(!$this->isStream($filename)){
        $this->writeStream($filename, json_encode(array("data" => array())));
        return array("data" => array());
      }
      return json_decode(file_get_contents($this->stream($filename)), true);
    }

    public function writeStream($filename, $content){
      file_put_contents($this->stream($filename), json_encode($content));
      return true;
    }

    public function readFile($filename){
      return $this->readStream($filename);
    }

    public function writeFile($filename, $entry){
      $fileContent = $this->readStream($filename);
      array_push($fileContent['data'], $entry);
      $this->writeStream($filename, $fileContent);
      return array(true, 'write successful');
    }

    public function findRead($filename, $key, $val){
      $fileContent = $this->readStream($filename);
      $searchFound = array();
      foreach($fileContent['data'] as $entry){
        if ($entry[$key] == $val) array_push($searchFound, $entry);
      }
      if(count($searchFound) != 0) return $searchFound;
      return array(false, "no entry found with the given criteria");
    }

    public function findWrite($filename, $find, $for, $key, $val){
      $fileContent = $this->readStream($filename);
      foreach($fileContent['data'] as $entry){
        if ($entry[$find] == $for){
          $entry[$key] = $entry[$value];
          return array(true, 'write successful');
        }
      }
      return array(false, "no entry found with the given criteria");
    }
  }

?>