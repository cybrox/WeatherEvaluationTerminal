<?php

  /* Generate random data for WET testing */
  $data = array();
  for($d = 1; $d <= 31; $d++){
    for($m = 0; $m <= 1440; $m += 15){
      array_push($data, array(
        "month" => "05",
        "day" => $d,
        "time" => "1401255132",
        "temperature" => rand(-20, 40),
        "wind_direction" => rand(0, 360),
        "wind_speed" => rand(0, 100),
        "rain_volume" => rand(0, 15),
        "humidity" => rand(0, 100)
      ));
    }
  }

  echo json_encode($data);

?>