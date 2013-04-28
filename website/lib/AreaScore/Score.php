<?php
namespace AreaScore
{
    class Score
    {
        private $_db;
        private $_areaId;

        public function setMySQL($conn) {
            $this->_db = $conn;
            return $this;
        }

        public function getAreaInfo() {
            $info = [];
            $query = "SELECT ea.*
                      FROM `esd-area` as ea
                      WHERE id = " . $this->_areaId;
            $res = $this->_db->query($query);
            if($res) {
                $info = $res->fetch_assoc(); 
                $query = "SELECT value, good_bad
                          FROM `esd-area-good-bad`
                          WHERE area_id = " . $this->_areaId;
                $res = $this->_db->query($query);
                if($res) {
                    while($row = $res->fetch_assoc()) {
                        $info[$row['good_bad']][] = $row['value'];
                    }
                }

            }
            return $info;
        }

        public function getScore($postcode) {
            $postcodePrefix = strtoupper($this->_processPostcode($postcode)['prefix']); 
            $query = "SELECT area_id
                      FROM `esd-postcode-area`
                      WHERE postcode = '$postcodePrefix'";

            $res = $this->_db->query($query);
            if($res) {
                $this->_areaId = $res->fetch_assoc()['area_id'];


                //Retrieve the global stats to be used in the computation of the score
                $stats = "SELECT metric_id, min, max, average, std_dev, category
                          FROM `esd-metric`";
                $statsRes = $this->_db->query($stats);
                if($statsRes) {
                    $metricGlobalStats = [];
                    while($stat = $statsRes->fetch_assoc()) {
                        $metricId = $stat['metric_id'];
                        unset($stat['metric_id']);

                        $metricGlobalStats[$metricId] = $stat;
                    }
                }

                //Retrieve area specific score
                $query = "SELECT value, metric_id
                          FROM `esd-metric-area`
                          WHERE area_id = " . $this->_areaId;

                $res = $this->_db->query($query);
                if($res) {
                    $metricsScore = [];
                    $globalScore = 0;
                    $metricCount = 0;
                    while($row = $res->fetch_assoc()) {
                        $isCrime = false;
                        if($metricGlobalStats[$row['metric_id']]['category'] == "crime") {
                            $isCrime = true;
                        }
                        $score = $this->_getMetricScore($metricGlobalStats[$row['metric_id']], $row['value'], $isCrime); 
                        $globalScore += $score;
                        $metricsScore[$metricGlobalStats[$row['metric_id']]['category']][$row['metric_id']] = round($score); 
                        $metricCount++;
                    }

                    $scoreByCat = [];
                    foreach($metricsScore as $title=>$cat) {
                        $total = 0;
                        foreach($cat as $score) {
                            $total += $score;
                        }
                        $scoreByCat[$title] = round($total / count($cat));
                    }
                    $areaScore = round($globalScore / $metricCount);
                }
            }
            return ["areaScore" => $areaScore, "breakdown" => $scoreByCat];
        }

        /************************/
        /*  INTERNAL FUNCTIONS  */
        /************************/
        private function _getMetricScore($globalStats, $value, $isCrime) {
            if($value > $globalStats['average']) {
                $upper = $globalStats['average'] + $globalStats['std_dev'];
                if($value > $upper) { //Are we out of the standard deviation
                    $perc = round((($globalStats['max'] - $upper) - ($globalStats['max'] - $value)) / ($globalStats['max'] - $upper), 3);
                    if(!$isCrime) {
                        $score = round(60 - (60 * $perc), 2);
                    } else {
                        $score = round(80 + (20 * $perc), 2);
                    }
                } else { //Use basic formula if within standard deviation
                    $perc = round((($globalStats['std_dev'] - ($value - $globalStats['average'])) / $globalStats['std_dev']), 3);
                    $score = round(70 - (10 * $perc), 2);
                }
            } else {
                $lower = $globalStats['average'] - $globalStats['std_dev'];
                if($value < $lower) { //Are we out of the standard deviation
                    $perc = round((($lower - $globalStats['min']) - ($value - $globalStats['min'])) / ($lower - $globalStats['min']), 3);
                    if(!$isCrime) {
                        $score = round(80 + (20 * $perc), 2);
                    } else {
                        $score = round(60 - (60 * $perc), 2);
                    }
                } else { //Use basic formula if within standard deviation
                    $perc = round((($globalStats['std_dev'] - ($globalStats['average'] - $value)) / $globalStats['std_dev']), 3);
                    $score = round(70 + (10 * $perc), 2);
                }
            }
            return $score;
        }

        private function _processPostcode($postcode) {
            $pattern = "/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/";

            $postcode = strtolower($postcode);
            if(strlen($postcode)>4 && substr($postcode,-4,1) != ' ') {
                if (preg_match('/(.+)(\d\w{0,2})$/',$postcode, $matches)){
                    $postcode = $matches[1] . ' ' . $matches[2];
                }
            }

            if (!preg_match($pattern, strtoupper($postcode))) {
                return false;
            } 
            return ["prefix" => $matches[1], "suffix" => $matches[2]];
        }
    }
}
