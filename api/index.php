<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  require_once('./wetapi.class.php');
  require_once('./lib/crunchdb.class.php');
  require_once('./lib/crunchroot.class.php');
  require_once('./lib/crunchtable.class.php');

  $wetapi = new WetApi();

  $requestLink = explode("/api/", $_SERVER['REQUEST_URI']);
  $requestData = explode("/", $requestLink[1]);

  if(count($requestData) < 2) $wetapi->publish(false, 'invalid api call, no method selected');
  if(!in_array($requestData[0], array('get','set'))) $wetapi->publish(false, 'invalid api call type');

  $requestFunc = $requestData[0].'_'.$requestData[1];
  array_splice($requestData, 0, 2);
  $requestArgs = $requestData;

  $wetapi->_call($requestFunc, $requestArgs);

?>