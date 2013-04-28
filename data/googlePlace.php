<?php
CONST API_KEY = "AIzaSyBaZiikURymmG3upinOeESk_O9rpiz8WrA";
CONST API_URL = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";

require_once "lib/MySQL.php";

$query = "SELECT *
          FROM `esd-postcode-area`";

$res = MySQL::getInstance()->getConn()->query($query);

$metrics = [1 => "bar", 2 => "night_club", 3 => "restaurant", 4 => "park"];
$data = [];
if($res) {
    $count = 0;
    while($row = $res->fetch_assoc()) {
        $data[$row['area_id']] = [];
        //Get LatLng
        $query = "SELECT lat, lng
                  FROM `os-postcodes`
                  WHERE postcode_prefix = '" . $row['postcode'] . "' 
                  LIMIT 1";
        $latLng = MySQL::getInstance()->getConn()->query($query)->fetch_assoc();
        $latLng = implode(",", $latLng);

        $params = ["location" => $latLng, "radius" => 500, "key" => API_KEY, "sensor" => 'false'];

        foreach($metrics as $metricId => $googleCat) {
            $count++;
            if(!isset($data[$row['area_id']][$metricId])) {
                $data[$row['area_id']][$metricId] = 0;
            }
            $params['types'] = $googleCat;
            $qs = http_build_query($params);
            $url = API_URL . $qs;

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            $results = curl_exec($ch);
            if($results) {
                $decoded = json_decode($results, true);
                $numResults = count($decoded['results']);
                $data[$row['area_id']][$metricId] += $numResults;
            } else {
                echo curl_error($ch) . PHP_EOL;
            }
        }
        if($count % 100 == 0) {
            echo "Have a break..." . PHP_EOL;
            sleep(2);
        }
    }

    foreach($data as $areaId=>$metrics) {
        foreach($metrics as $metricId=>$count) {
            $query = "INSERT INTO `esd-metric-area` VALUES($metricId, $areaId, $count)";
            MySQL::getInstance()->getConn()->query($query);
        }
    }


}
