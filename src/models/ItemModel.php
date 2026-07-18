<?php
    require_once __DIR__ . '/../database/DatabaseConnection.php';

    class ItemsModel extends DatabaseConnection {
        private $table = "ITEMS";
        private $tokenTable = "AUTHTOKEN";
        public function getAllItems ($page = 1) {
            $limit = 100;
            $offset = ($page - 1) * $limit;
            $query = "SELECT * FROM {$this->table} LIMIT $offset, $limit";
            return parent::getData ($query);
        }
        public function getItemById ($id) {
            $query = "SELECT * FROM {$this->table} WHERE ITEMID = '$id'";
            return parent::getData ($query);
        }
        public function createItem ($name, $description) {
            $query = "INSERT INTO {$this->table} (ITEMNAME, ITEMDESCRIPTION) VALUES ('$name', '$description')";
            return parent::getIdRow ($query);
        }
        public function updateItem ($id, $name, $description) {
            $query = "UPDATE {$this->table} SET ITEMNAME = '$name', ITEMDESCRIPTION = '$description' WHERE ITEMID = '$id'";
            $result = parent::getAffectedRow ($query);
            return $result >= 1;
        }
        public function deleteItem ($id) {
            $query = "DELETE FROM {$this->table} WHERE ITEMID = '$id'";
            $result = parent::getAffectedRow ($query);
            return $result >= 1;
        }
        public function validateToken ($token) {
            $query = "SELECT TOKID FROM {$this->tokenTable} WHERE TOKTOKEN = '$token' AND TOKACTIVE = 'ACTIVE'";
            $result = parent::getData ($query);
            return !empty ($result);
        }
        public function updateToken ($dat) {
            $query = "UPDATE {$this->tokenTable} SET TOKACTIVE = 'INACTIVE' WHERE TOKCREATEDAT > '$dat'";
            $res = parent::getAffectedRow ($query);
            if ($res){
                return $res;
            }
            else {
                return 0;
            }
        }
    }
?>
