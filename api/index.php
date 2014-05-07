<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  require_once('./core/notification-handler.class.php');
  require_once('./core/weatherdata-handler.class.php');
  require_once('./core/database-handler.class.php');
  require_once('./core/request-handler.class.php');

  $requestLink = explode("/api/", $_SERVER['REQUEST_URI']);
  $requestData = explode("/", $requestLink[1]);

  $requestHandler = new RequestHandler($requestData);

?>