<?php
    require_once __DIR__ . '/../models/ItemModel.php';
    require_once __DIR__ . '/../web/Response.php';
    require_once __DIR__ . '/../utils/transformItemObject.php';
    class ItemsController {
        private $model;
        private $httpState;
        public function __construct () {
            $this->model = new ItemsModel ();
            $this->httpState = new Response ();
        }
        public function getAll ($page = 1, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            $items = $this->model->getAllItems ($page);
            $stat = $this->httpState->res;
            $stat ["msg"] = "Get all data";
            $stat ["data"] = array_map ('transformItem', $items);
            return $stat;
        }
        public function getOne ($id, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            $item = $this->model->getItemById ($id);
            $stat = $this->httpState->res;
            $stat ["msg"] = "Get data";
            $stat ["data"] = transformItem ($item [0]);
            return $stat;
        }
        public function post ($json, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            $data = json_decode ($json, true);
            if (!isset ($data ["name"], $data ["description"])) {
                return $this->httpState->res400 ();
            }
            $itemId = $this->model->createItem ($data ["name"], $data ["description"]);
            if ($itemId) {
                $stat = $this->httpState->res;
                $stat ["msg"] = "Saved data";
                $stat ["data"] = $itemId;
                return $stat;
            } 
            else {
                return $this->httpState->res500 ();
            }
        }
        public function put ($json, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            $data = json_decode ($json, true);
            if (!isset ($data ["id"], $data ["name"], $data ["description"])) {
                return $this->httpState->res400 ();
            }
            $updated = $this->model->updateItem ($data ["id"], $data ["name"], $data ["description"]);
            if ($updated) {
                $stat = $this->httpState->res;
                $stat ["msg"] = "Updated data";
                $stat ["data"] = $data ["id"];
                return $stat;
            } 
            else {
                return $this->httpState->res500 ();
            }
        }
        public function delete ($json, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            $data = json_decode ($json, true);
            if (!isset ($data ["id"])) {
                return $this->httpState->res400 ();
            }
            $deleted = $this->model->deleteItem ($data ["id"]);
            if ($deleted) {
                $stat = $this->httpState->res;
                $stat ["msg"] = "Deleted data";
                $stat ["data"] = $data ["id"];
                return $stat;
            } 
            else {
                return $this->httpState->res500 ();
            }
        }
    }
?>