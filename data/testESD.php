<?php
require_once "lib/MySQL.php";
require_once "lib/ESD.php";

$esd = (new ESD("GoogleHackPPK","hackpass"))->setMySQL(MySQL::getInstance()->getConn());

$metrics = ["crime" => [1037, 329, 330, 195,81, 82],
            "school" => [63,64,65,66,369],
            "transport" => [44,141],
            "satisfaction" => [666,667,669,670],
            "population" => [821,822,1540,1544,1940,1941]];

$query = "SELECT id AS area_id, area_code, identifier
          FROM `esd-area`";

$res = MySQL::getInstance()->getConn()->query($query);
if($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['identifier'] . "..." . PHP_EOL;
        foreach($metrics as $met) {
            foreach($met as $id) {
                $esd->getData($id, $row);
            }
        }
        echo "Done" . PHP_EOL;
        echo "================" . PHP_EOL;
        sleep(2);
    }
}
