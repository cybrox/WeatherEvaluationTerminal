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

  $wetapi = new WetApi('../data/');

  $requestLink = explode("/api/", $_SERVER['REQUEST_URI']);
  $requestData = explode("/", $requestLink[1]);

  if(count($requestData) < 2) $wetapi->publish(false, 'invalid api call, no method selected');
  if($requestData[0] == 'create') { die("You may not publish custom data on this page."); }

  $requestFunc = $requestData[0].'_'.$requestData[1];
  array_splice($requestData, 0, 2);
  $requestArgs = $requestData;

  $wetapi->_call($requestFunc, $requestArgs);

?>