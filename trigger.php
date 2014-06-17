<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  *
  * Automatically pull data from a online weather API
  * to get valid weather data to display.
  * This API read trigger will be called by a cronjob
  * every 15 minutes by https://cron-job.org/
  */

  require_once('api/wetapi.class.php');
  require_once('api/lib/crunchdb.class.php');
  require_once('api/lib/crunchroot.class.php');
  require_once('api/lib/crunchtable.class.php');

  define("APIBASE", "http://api.openweathermap.org/data/2.5/");
  define("APITYPE", "&type=like&units=metric&mode=json");
  define("APICITY", "Zurich");

  $target = APIBASE."find?q=".APICITY.APITYPE;
  $data = json_decode(file_get_contents($target), true);
  $wapi = new WetApi('./data/');

  $rain = 0;
  $time = time();
  $temp = $data['list'][0]['main']['temp'];
  $wdir = $data['list'][0]['wind']['deg'];
  $wspd = $data['list'][0]['wind']['speed'];
  $humd = $data['list'][0]['main']['humidity'];

  $wapi->_call('create_weatherdata', array($time, $temp, $wdir, $wspd, $rain, $humd));

?>