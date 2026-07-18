<?php
    require_once __DIR__ . '/../database/DatabaseConnection.php';
    class AuthModel extends DatabaseConnection {
        private $userTable = "AUTHUSER";
        private $tokenTable = "AUTHTOKEN";
        public function encryptPassword ($password) {
            return parent::encryptPassword ($password);
        }
        public function getUserData ($username) {
            $query = "SELECT AUTID, AUTNAME, AUTCITY, AUTAGE, AUTNICKNAME, AUTPASSWORD FROM $this->userTable WHERE AUTNICKNAME = '$username'";
            $result = parent::getData ($query);
            return $result [0] ?? null;
        }
        public function userExists ($username) {
            return $this->getUserData ($username) !== null;
        }
        public function getUserById ($userId) {
            $query = "SELECT AUTID, AUTNAME, AUTCITY, AUTAGE, AUTNICKNAME FROM $this->userTable WHERE AUTID = '$userId'";
            $result = parent::getData ($query);
            return $result [0] ?? null;
        }
        public function createUser ($name, $city, $age, $username, $password) {
            $encryptedPassword = $this->encryptPassword ($password);
            $query = "INSERT INTO $this->userTable (AUTNAME, AUTCITY, AUTAGE, AUTNICKNAME, AUTPASSWORD) VALUES ('$name', '$city', '$age', '$username', '$encryptedPassword')";
            return parent::getIdRow ($query);
        }
        public function updateUser ($userId, $name, $city, $age, $username, $password) {
            $encryptedPassword = $this->encryptPassword ($password);
            $query = "UPDATE $this->userTable SET AUTNAME = '$name', AUTCITY = '$city', AUTAGE = '$age', AUTNICKNAME = '$username', AUTPASSWORD = '$encryptedPassword' WHERE AUTID = '$userId'";
            return parent::getIdRow ($query);
        }
        public function deleteUser ($userId) {
            $query = "DELETE FROM $this->userTable WHERE AUTID = '$userId'";
            $result = parent::getIdRow ($query);
            return $result;
        }
        public function generateToken ($userId) {
            $token = bin2hex (random_bytes (16));
            $date = date ("Y-m-d H:i:s");
            $status = "ACTIVE";
            $query = "INSERT INTO $this->tokenTable (TOKUSERID, TOKTOKEN, TOKCREATEDAT, TOKACTIVE) VALUES ('$userId', '$token', '$date', '$status')";
            $inserted = parent::getIdRow ($query);
            return $inserted ? $token : null;
        }
        public function validateToken ($token, $id) {
            $query = "SELECT TOKID FROM {$this->tokenTable} WHERE TOKTOKEN = '$token' AND TOKACTIVE = 'ACTIVE' AND TOKUSERID = '$id'";
            $result = parent::getData ($query);
            return !empty ($result);
        }
    }
?>