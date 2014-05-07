<?php

	/**
	* Weather Evaluation Tool - Webinterface
	*
	* Written and copyrighted 2014+
	* by Sven Marc 'cybrox' Gehring
	*
	* Licensensed under CC-NC-BY-SA
	*/


	require_once("./core/DataHandler.class.php");

	$requestLink = explode("/api/", $_SERVER['REQUEST_URI']);
	$requestData = explode("/", $requestLink[1]);

	$dataHandler = new DataHandler($requestData);

?>