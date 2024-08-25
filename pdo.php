<?php 
    class Database {
        private $dbHost = "localhost";
        private $dbName = "stayhub";
        private $dbUser = "root";
        private $dbPassword = "";
        public $sqlConn;
        public function getConnection(){
            $this->sqlConn = null;
            try{
                $this->sqlConn = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUser, $this->dbPassword);
                $this->sqlConn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->sqlConn;
        }
    }  
?>