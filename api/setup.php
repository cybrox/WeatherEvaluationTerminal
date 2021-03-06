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

  $dir = '../data';

  if(is_dir($dir)){
    foreach(scandir($dir) as $file) {
      if ('.' === $file || '..' === $file) continue;
      else unlink("$dir/$file");
    }
  }
  rmdir($dir);
  mkdir($dir);

  $cdb = new crunchDB($dir);
  $cdb->create('notification');

  echo 'setup complete. thanx!';

?>