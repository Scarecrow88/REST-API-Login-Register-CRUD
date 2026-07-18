<?php
    class DatabaseConnection {
        private $host;
        private $user;
        private $password;
        private $database;
        private $port;
        protected $connection;
        public function __construct () {
            $listJson = $this->dataConfig ();
            foreach ($listJson as $key => $value) {
                $this->host = $value ['host'];
                $this->user = $value ['user'];
                $this->password = $value ['password'];
                $this->database = $value ['database'];
                $this->port = $value ["port"];
            }
            try {
                $this->connection = new mysqli ($this->host, $this->user, $this->password, $this->database, $this->port);                
                if ($this->connection->connect_error) {
                    throw new Exception ("Error de conexión: " . $this->connection->connect_error);
                }
            }
            catch (Exception $e) {
                die ("Excepción capturada: " . $e->getMessage ());
            }
        }
        private function dataConfig () {
            $dir = dirname (__FILE__);
            $jsonData = file_get_contents ($dir . "/../config" . "/config.json");
            if ($jsonData) {
                return json_decode ($jsonData, true);
            }
            else {
                echo "Error";
            }
        }
        private function toUTF8 ($arr) {
            array_walk_recursive ($arr, function (&$ite, $key) {
                if (!mb_detect_encoding ($ite, "utf-8", true)) {
                    $ite = mb_convert_encoding ($ite, 'utf-8', "ISO-8859-1"); 
                }
            });
            return $arr;
        }
        public function getData ($sql) {
            $res = $this->connection->query ($sql);
            $resArr = array ();
            foreach ($res as $ite) {
                $resArr [] = $ite;
            }
            return $this->toUTF8 ($resArr);
        }
        public function getAffectedRow ($sql) {
            $res = $this->connection->query ($sql);
            return $this->connection->affected_rows;
        }
        public function getIdRow ($sql) {
            $res = $this->connection->query ($sql);
            $rows = $this->connection->affected_rows;
            if ($rows >= 1) {
                return $this->connection->insert_id;
            }
            else {
                return 0;
            }
        }
        protected function encryptPassword ($pass) {
            return md5 ($pass);
        }
    }
?>