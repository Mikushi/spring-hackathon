<?php
require_once "lib/MySQL.php";
require_once "lib/ESD.php";

$esd = (new ESD("GoogleHackPPK","hackpass"))->setMySQL(MySQL::getInstance()->getConn());

$metrics = ["environment" => [223,418,1771,1788,1789]];

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
    }
}
