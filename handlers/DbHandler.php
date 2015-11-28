<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 4:23 AM
 */

require_once "DbConfig.php";

class DbHandler{

    static public function getConnection(){
        if( DbConfig::CONFIG_DB_TYPE == "sqlite" ){
            return static::getSQLiteConnection();
        }
        /*
        else if( DbConfig::CONFIG_DB_TYPE == "pdo" ){
            return static::getPDOConnection();
        }
        else if( DbConfig::CONFIG_DB_TYPE == "mysqli" ){
            return static::getMysqliConnection();
        }
        */
    }

    /* ============================ protected ============================= */

    static protected function getSQLiteConnection(){

        static::checkSQLiteDb();

        $handle = new SQLite3(DbConfig::CONFIG_DB_LOCATION);
        return $handle;
    }

    static protected function checkSQLiteDb(){
        $location = DbConfig::CONFIG_DB_LOCATION;
        //assume that if file exists, the tables are also already there
        if( !file_exists($location) ){
            touch($location);
            static::initializeSQLiteDbTables($location);
        }
    }

    static protected function initializeSQLiteDbTables($location){

        $sqlite = new SQLite3($location);

        $tables_setup = DbConfig::getDbTablesSetup();

        foreach($tables_setup as &$query){
            $sqlite->exec($query);
        }
        unset($table);
    }
    /*
    static protected function getPDOConnection(){

        try {
            $conn = new PDO('mysql:host='.DbConfig::CONFIG_DB_HOST.';dbname='.DbConfig::CONFIG_DB_LOCATION, DbConfig::CONFIG_DB_USER, DbConfig::CONFIG_DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    static protected function getMysqliConnection(){
        //Open a new connection to the MySQL server
        $mysqli = new mysqli(DbConfig::CONFIG_DB_HOST, DbConfig::CONFIG_DB_USER, DbConfig::CONFIG_DB_PASS, DbConfig::CONFIG_DB_LOCATION);

        //Output any connection error
        if ($mysqli->connect_error) {
            die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        }

        return $mysqli;
    }
    */
}

