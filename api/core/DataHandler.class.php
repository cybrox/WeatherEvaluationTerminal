<?php

	/**
	* Weather Evaluation Tool - Webinterface
	* Data input api class, will receive data from the station.
	*
	* This class handles a simple API call from the station
	* to the /api/ directory with the following parameter syntax:
	* /[time]/[temperature]/[winddirection]/[windspeed]/[rainvolume]/[humidity]
	* The data will be checked, formatted and eventually stored
	* in its respective month's .json file in the /data/ directory.
	*
	* Written and copyrighted 2014+
	* by Sven Marc 'cybrox' Gehring
	*
	* Licensensed under CC-NC-BY-SA
	*/

	define("DATADIR", "../data/");
	define("LOGSIZE", 200);


	class DataHandler {

		private $dataFile;				// File where the respective data will be saved
		private $dataArray;				// Complete array with all input data
		private $dataTimestamp;			// Input data timestamp
		private $dataTemperature;		// Input data temparature
		private $dataWinddirection;		// Input data wind direction
		private $dataWindspeed;			// Input data wind speed
		private $dataRainvolume;		// Input data rain volume
		private $dataHumidity;			// Input data humidity


		/**
		 * @method construct
		 * @name Constructor
		 * @desc Class constructor, will read the values from the
		 *	API request and set them for further progression.
		 *	URL-Format: .../[time]/[temperature]/[winddirection]/[windspeed]/[rainvolume]/[humidity]
		 * @parameter {array} _ Array with all request parameters
		 */
		public function __construct($requestData){
			
			if(count($requestData) != 6) $this->handleError("invalid request format");

			$this->dataArray         = $requestData;
			$this->dataTimestamp     = intval($requestData[0]);
			$this->dataTemperature   = intval($requestData[1]);
			$this->dataWinddirection = intval($requestData[2]);
			$this->dataWindspeed     = intval($requestData[3]);
			$this->dataRainvolume    = intval($requestData[4]);
			$this->dataHumidity      = intval($requestData[5]);

			$this->getTargetFile();
			$this->checkInputData();
			$this->saveInputData();
			$this->handleResponse();
		}


		/**
		 * @method getTarget File
		 * @name Get Target File
		 * @desc Generate the name of the target file and
		 *	generate it if it doesn't exist.
		 */
		private function getTargetFile(){

			if(!@preg_match("#[0-9]{1,}#", $this->dataTimestamp)) $this->handleError("invalid timestamp");

			$dataDate = date("Y-m", $this->dataTimestamp);
			$dataFile = DATADIR.$dataDate.".json";

			if(!@preg_match("#[0-9]{4}\-[0-9]{2}#", $dataDate)) $this->handleError("invalid timestamp");
			if(!file_exists($dataFile)){
				file_put_contents($dataFile, "{\"created\": \"".time()."\", \"data\": []}");
			}

			$this->dataFile = $dataFile;

		}


		/**
		 * @method checkInputData
		 * @name Check Input Data
		 * @desc Check if the input data is correct
		 */
		private function checkInputData(){
			if($this->dataTimestamp == 0){
				$this->handleError("invliad timestamp");
			}

			if($this->dataTemperature < -20 || $this->dataTemperature > 43){
				$this->handleWarning("invalid temparature value", $this->dataTemperature);
			}

			if($this->dataWinddirection < 0 || $this->dataWinddirection > 360){
				$this->handleWarning("invalid wind direction", $this->dataWinddirection);
			}

			if($this->dataWindspeed < 0 || $this->dataWindspeed > 100){
				$this->handleWarning("invalid windspeed", $this->dataWindspeed);
			}

			if($this->dataRainvolume < 0 || $this->dataRainvolume > 15){
				$this->handleWarning("invalid rain volume", $this->dataRainvolume);
			}

			if($this->dataHumidity < 0 || $this->dataHumidity > 100){
				$this->handleWarning("invliad humidity", $this->dataHumidity);
			}
		}


		/**
		 * @method saveInputData
		 * @name Save Input Data
		 * @desc Save the input data to the respective file
		 */
		private function saveInputData(){

			$dataBlob = array(
				"day"           => date("d", time()),
				"time"          => $this->dataArray[0],
				"temparature"   => $this->dataArray[1],
				"winddirection" => $this->dataArray[2],
				"windspeed"     => $this->dataArray[3],
				"rainvolume"    => $this->dataArray[4],
				"humidity"      => $this->dataArray[5]
			);

			$dataFile = DATADIR.$this->dataFile;
			$dataInfo = json_decode(file_get_contents($dataFile), true);

			array_push($dataInfo['data'], $dataBlob);

			file_put_contents($dataFile, json_encode($dataInfo));

		}


		/**
		 * @method handleResponse
		 * @name Handle Response
		 * @desc Handle the API success response
		 */
		private function handleResponse(){
			echo json_encode(array("success" => true, "data" => "added entry to ".$this->dataFile));
		}


		/**
		 * @method handleWarning
		 * @name Handle Warning
		 * @desc Handle any warning that might occur on data checks
		 * @param {string} message - The warning message
		 * @param {value} int - The given (wrong) value
		 */
		private function handleWarning($message, $value){
			if(!file_exists(DATADIR."errorlog.json")){
				file_put_contents(DATADIR."errorlog.json", "{\"messages\": []}");
			}

			$dataFile = DATADIR."errorlog.json";
			$dataInfo = json_decode(file_get_contents($dataFile), true);

			$dataBlob = array(
				"time" => time(),
				"message" => $message,
				"value" => $value
			);

			/* Remove fist when log is full */
			if(count($dataInfo['messages']) >= LOGSIZE) array_splice($dataInfo['messages'], 0, 1);
			array_push($dataInfo['messages'], $dataBlob);

			file_put_contents($dataFile, json_encode($dataInfo));	
		}


		/**
		 * @method handleError
		 * @name Handle Error
		 * @desc Handle any error that might occur in here
		 * @param {string} message - The error message
		 */
		private function handleError($message){
			die(json_encode(array("success" => false, "data" => $message)));
		}
	}

?>