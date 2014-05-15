<?php

  /**
  * Weather Evaluation Tool - Webinterface
  *
  * Written and copyrighted 2014+
  * by Sven Marc 'cybrox' Gehring
  *
  * Licensensed under CC-NC-BY-SA
  */

  require_once('./lib/crunchdb.class.php');
  require_once('./lib/crunchroot.class.php');
  require_once('./lib/crunchtable.class.php');

  $cdb = new crunchDB('../data');
  $cdb->create('weatherdata');
  $cdb->create('notification');

?>