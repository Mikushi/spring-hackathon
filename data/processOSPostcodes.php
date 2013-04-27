<?php
require_once "lib/PhpCoord.php";

$postcodeDir = "../../postcodes/";

$londonPostcodes = ["e.csv","ec.csv","n.csv","nw.csv","se.csv","sw.csv","w.csv","wc.csv"];

foreach($londonPostcodes as $file) {
    $filePath = $postcodeDir . $file;
}
