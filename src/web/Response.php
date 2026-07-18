<?php
    class Response {
        public $res = [
            "error" => false,
            "status" => "200",
            "msg" => null,
            "data" => array ()
        ];
        public function res200 ($dat = "Incorrect data") {
            $this->res ["error"] = true;
            $this->res ["status"] = "200";
            $this->res ["msg"] = $dat;
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res405 () {
            $this->res ["error"] = true;
            $this->res ["status"] = "405";
            $this->res ["msg"] = "Method not allowed";
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res404 ($val = "Resource not found") {
            $this->res ["error"] = true;
            $this->res ["status"] = "404";
            $this->res ["msg"] = $val;
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res400 ($val = "Format not allowed or missing data") {
            $this->res ["error"] = true;
            $this->res ["status"] = "400";
            $this->res ["msg"] = $val;
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res401 ($val = "Not authorized, invalid token") {
            $this->res ["error"] = true;
            $this->res ["status"] = "401";
            $this->res ["msg"] = $val;
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res409 ($val = "The username is already in use") {
            $this->res ["error"] = true;
            $this->res ["status"] = "409";
            $this->res ["msg"] = $val;
            $this->res ["data"] = null;
            return $this->res;
        }
        public function res500 ($val = "Internal server error"){
            $this->res ['error'] = true;
            $this->res ['status'] = "500";
            $this->res ['msg'] = $val;
            $this->res ['data'] = null;
            return $this->res;
        }
    }
?>