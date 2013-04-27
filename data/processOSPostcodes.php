<?php
require_once "lib/PhpCoord.php";
require_once "lib/MySQL.php";

$postcodeDir = "../../postcodes/";

$londonPostcodes = ["e.csv","ec.csv","n.csv","nw.csv","se.csv","sw.csv","w.csv","wc.csv"];

$dbConn = MySQL::getInstance()->getConn();
$count = 0;
$queryHead = "INSERT INTO `os-postcodes`(`postcode_prefix`,`postcode_suffix`,`lat`,`lng`) VALUES";
foreach($londonPostcodes as $file) {
    $filePath = $postcodeDir . $file;
    $fh = fopen($filePath, "r+");
    $values = [];
    while($data = fgetcsv($fh)) {
        $count++;
        if($count < 17885) {
            continue;
        }
        $postcode = $data[0];
        $easting = $data[2];
        $northing = $data[3]; 

        $osRef = new OSRef($easting, $northing);
        $latLng = $osRef->toLatLng();
        
        $part = explode(" ", $postcode);
        if(!isset($part[1])) {
            $postcode = strtolower($part[0]);
            if(strlen($postcode)>4 && substr($postcode,-4,1) != ' ') {
                if (preg_match('/(.+)(\d\w{0,2})$/',$postcode, $matches)){
                    $postcode = strtoupper($matches[1] . ' ' . $matches[2]);
                }
            }
            $part = explode(" ", $postcode);
        }
        $postcodePrefix = $part[0];
        if(!isset($part[2])) {
            $postcodeSuffix = $part[1];
        } else {
            $postcodeSuffix = $part[2];
        }

        $values[] = "('$postcodePrefix','$postcodeSuffix'," . $latLng['lat'] . "," . $latLng['lng'] . ")";
        if(count($values) > 999) {
            echo "Pushing to DB..." . PHP_EOL;
            $gluedValues = implode(",", $values);
            $query = $queryHead . $gluedValues;
            $dbConn->query($query);
            $values = [];
        }
    }
}
