<?php 
require_once "lib/MySQL.php";

$query = "select ema.metric_id, AVG(value) as average, MIn(value) as minimum, MAX(value) as maximum, SQRT(AVG(value*value) - AVG(value)*AVG(value)) AS std_dev
from `esd-metric-area` as ema
join `esd-metric` AS em
on em.metric_id = ema.metric_id
group by ema.metric_id";

$res = MySQL::getInstance()->getConn()->query($query);
if($res) {
    while($row = $res->fetch_assoc()) {
        $query = "UPDATE `esd-metric`
                  SET min = %s,
                  max = %s,
                  average = %s,
                  std_dev = %s
                  WHERE metric_id = %s";
        MySQL::getInstance()->getConn()->query(sprintf($query, $row['minimum'], $row['maximum'], $row['average'], $row['std_dev'], $row['metric_id']));
    }
}
