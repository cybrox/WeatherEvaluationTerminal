<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  class WeatherdataHandler {
    private $db;
    private $nh;
    private $dataTimestamp;
    private $dataTemperature;
    private $dataWinddirection;
    private $dataWindspeed;
    private $dataRainvolume;
    private $dataHumidity;

    public function __construct(){
      $this->db = new DatabaseHandler();
      $this->nh = new NotificationHandler();
    }

    private function isMonth($month){
      return preg_match("#[0-9]{4}\-[0-9]{2}#", $month);
    }

    public function readWeatherdata($reqParams){
      $month = $reqParams[2];
      if(!$this->isMonth($month)) return array(false, "invalid month value");
      return $this->db->readFile($month);
    }

    public function writeWeatherdata($reqParams){
      if(count($reqParams) != 9) return array(false, "invalid weather data");

      $this->dataTimestamp     = intval($reqParams[3]);
      $this->dataTemperature   = intval($reqParams[4]);
      $this->dataWinddirection = intval($reqParams[5]);
      $this->dataWindspeed     = intval($reqParams[6]);
      $this->dataRainvolume    = intval($reqParams[7]);
      $this->dataHumidity      = intval($reqParams[8]);

      $isValid = $this->checkInputData();
      if($isValid !== true) return $isValid;

      $dataDate = date("Y-m", $this->dataTimestamp);
      $dataBlob = array(
        "day"           => date("d", $this->dataTimestamp),
        "time"          => $this->dataTimestamp,
        "temparature"   => $this->dataTemperature,
        "winddirection" => $this->dataWinddirection,
        "windspeed"     => $this->dataWindspeed,
        "rainvolume"    => $this->dataRainvolume,
        "humidity"      => $this->dataHumidity
      );

      return $this->db->writeFile($dataDate, $dataBlob);
    }

  /**
   * @method checkInputData
   * @name Check Input Data
   * @desc Check if the input data is correct
   */
  private function checkInputData(){
    if($this->dataTimestamp == 0)
      return array(false, "invalid timestamp");

    if($this->dataTemperature < -20 || $this->dataTemperature > 43)
      $this->nh->writeNotification("invalid temparature value(".$this->dataTemperature.")");

    if($this->dataWinddirection < 0 || $this->dataWinddirection > 360)
      $this->nh->writeNotification("invalid wind direction(".$this->dataWinddirection.")");

    if($this->dataWindspeed < 0 || $this->dataWindspeed > 100)
      $this->nh->writeNotification("invalid windspeed(".$this->dataWindspeed.")");

    if($this->dataRainvolume < 0 || $this->dataRainvolume > 15)
      $this->nh->writeNotification("invalid rain volume(".$this->dataRainvolume.")");

    if($this->dataHumidity < 0 || $this->dataHumidity > 100)
      $this->nh->writeNotification("invliad humidity(".$this->dataHumidity.")");

    return true;
  }
}

?>