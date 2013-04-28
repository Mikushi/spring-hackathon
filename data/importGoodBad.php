<?php
require_once "lib/MySQL.php";

$path = "../../data_export/goodBad.csv";

$fh = fopen($path, "r+");

while($data = fgetcsv($fh, 0, ";")) {
    $query = "INSERT INTO `esd-area-good-bad` (area_id, value, good_bad) VALUES(" . $data[0] . ", '" . $data[1] . "', '" . $data[2] . "')";
    MySQL::getInstance()->getConn()->query($query);
}
