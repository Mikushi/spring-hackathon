<?php
class MySQL
{
    const USER = "areascore";
    const PWD = "h4ck4th0n_!";
    const HOST = "localhost";
    const DBNAME = "areascore";

    private $_conn;
    private $_siteConn;
    private static $_instance = null;

    private function __construct() {
        $this->_conn = new \MySQLi(self::HOST, self::USER, self::PWD, self::DBNAME);
    }

    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getConn() {
        return $this->_conn;
    }

    public function sanitize($data) {
        foreach($data as $key=>$entry) {
            if(is_string($entry)) {
                $data[$key] = $this->_conn->real_escape_string($entry);
            }
        }
        return $data;
    }

    public function prepareUpdate($data) {
        $sqlStr = array();
        foreach($data as $field=>$value) {
            $sqlStr[] = "$field = '$value'";
        }
        return $sqlStr;
    }

    public function getTotalCount() {
        $count = $this->_conn->query("SELECT FOUND_ROWS() AS total")->fetch_assoc();
        return $count['total'];
    }
}	
