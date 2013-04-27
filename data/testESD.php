<?php
require_once "lib/ESD.php";

$esd = new ESD("GoogleHackPPK","hackpass");

$esd->getMetricDetails(81);
//$data = $esd->getData(81, "e63hg");
//var_dump($data);
