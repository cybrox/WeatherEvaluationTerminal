<?php

	/**
	* Weather Evaluation Tool - Webinterface
	*
	* Written and copyrighted 2014+
	* by Sven Marc 'cybrox' Gehring
	*
	* Licensensed under CC-NC-BY-SA
	*/


	header('Content-type: application/json');

	$targetFile = "../data/".$_GET['f'].".json";

	if(file_exists($targetFile)){

		$fileData = json_decode(file_get_contents($targetFile));
		echo json_encode(array("success" => true, "error" => "", "data" => $fileData));

	} else {
		echo json_encode(array("success" => false, "error" => "no file", "data" => ""));
	}

?>