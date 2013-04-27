<?php
require_once "MySQL.php";
class ESD
{
    const BASE_URL = "http://production.webservices.esd.org.uk/";

    private $_key;
    private $_secret;
    private $_db;
    private $_ignoreList = [];

    public function __construct($key, $secret) {
        $this->_key = $key;
        $this->_secret = $secret;
        $this->_db = MySQL::getInstance()->getConn();
        $this->_ignoreList[] = 'E31000046';
        $this->_ignoreList[] = 'E09000001';
    }

    public function getMetricDetails($metricId) {
        $url = self::BASE_URL . "InformPlus/metricTypes/$metricId";
        $url = $this->_signUrl($url);

        $data = $this->_call($url);
        $label = $data['metricType']['label'];
        return $label;
    }

    public function getData($metricId, $postcode) {
        $pattern = "/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/";

        $postcode = strtolower($postcode);
        if(strlen($postcode)>4 && substr($postcode,-4,1) != ' ') {
            if (preg_match('/(.+)(\d\w{0,2})$/',$postcode, $matches)){
                $postcode = $matches[1] . ' ' . $matches[2];
            }
        }

        if (!preg_match($pattern, strtoupper($postcode))) {
            return false;
        } else {
            $areaCode = $this->_getAreaCode(str_replace(" ", "", $postcode)); 
        }

        if($areaCode) {
            $return = [];
            $query = "SELECT desc, value
                      FROM `esd-metric`
                      JOIN `esd-metric-area`
                      USING metric_id
                      WHERE area_id = " . $areaCode['area_id'];
                      
            $res = $this->_db->query($query);
            if(!$res) {
                $url = self::BASE_URL . "InformPlus/data?query1.metricType=$metricId&query1.area=" . $areaCode['area_code'] . "&query1.period=latest";
                $url = $this->_signUrl($url);
                $data = $this->_call($url);

                //Save metric label to DB for later processing and understanding
                $query = "SELECT `desc` FROM `esd-metric` WHERE metric_id = $metricId";
                $res = $this->_db->query($query);
                if($res->num_rows == 0) {
                    $desc = $this->getMetricDetails($metricId);
                    $query = "INSERT INTO `esd-metric` VALUES($metricId, '$desc')";
                    $this->_db->query($query);
                } else {
                    $desc = $res->fetch_assoc()['desc'];
                }

                $value = $data['rows'][0]['values'][0]['source'];
                $query = "INSERT INTO `esd-metric-area`(`metric_id`, `area_id`, `value`) VALUES($metricId, " . $areaCode['area_id'] . ", $value)";
                $this->_db->query($query);
                $return = ["desc" => $desc, "value" => $value];
            } else {
                $return = $res->fetch_assoc();
            }

            return $return;
        } else {
            return false;
        }
    }

    /************************/
    /*  INTERNAL FUNCTIONS  */
    /************************/
    private function _getAreaCode($postcode) {
        $query = "SELECT area_code, ea.id AS area_id
                  FROM `esd-postcode-area` AS epa
                  JOIN `esd-area` AS ea
                  ON epa.area_id = ea.id
                  WHERE postcode = '$postcode'
                  LIMIT 1";

        $res = $this->_db->query($query);
        if($res->num_rows == 0) {
            $url = self::BASE_URL . "InformPlus/organisations?area=$postcode";
            $url = $this->_signUrl($url);
    
            $data = $this->_call($url);
            if($data) {
                $areaCodeToUse = false;
                $areaIdToUse = false;
                foreach($data['organisation-array'] as $area) {
                    $identifier = $area['governs']['label'];
                    $areaCode = $area['governs']['identifier'];

                    if(in_array($areaCode, $this->_ignoreList)) {
                        continue;
                    }
                    if(!$areaCodeToUse) {
                        $areaCodeToUse = $areaCode;
                    }

                    $query = "INSERT INTO `esd-area` (`identifier`,`area_code`) VALUES('$identifier','$areaCode')";
                    $this->_db->query($query);
                    $areaId = $this->_db->insert_id;
                    if(!$areaIdToUse) {
                        $areaIdToUse = $areaId;
                    }

                    $query = "INSERT INTO `esd-postcode-area` (`area_id`, `postcode`) VALUES($areaId, '$postcode')";
                    $this->_db->query($query);
                }

                return ['area_code' => $areaCodeToUse, 'area_id' => $areaIdToUse];
            }
        } else {
            return $res->fetch_assoc();
        }
        return false;
    }

    private function _call($url) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);
        if($data) {
            $decoded = json_decode($data, true);
            return $decoded;
        } else {
            echo curl_error($ch) . PHP_EOL;
            return false;
        }
    }

    private function _signUrl($url) {
        $url = urldecode($url);

        if (strpos($url,'?') !== false) {
            $url .= "&";
        } else {
            $url .= "?";
        }
        $url .= "ApplicationKey=" . $this->_key;

        $signature = hash_hmac("sha1", $url, $this->_secret);
        $url .= "&Signature=" . $this->_hexToBase64($signature);

        return $url;
    }

    private function _hexToBase64($hex){
        $return = '';
        foreach(str_split($hex, 2) as $pair) {
            $return .= chr(hexdec($pair));
        }
        return base64_encode($return);
    }
}
