<?php
    require_once __DIR__ . '/../models/AuthModel.php';
    require_once __DIR__ . '/../web/Response.php';
    require_once __DIR__ . '/../utils/transformUserObject.php';
    class AuthController {
        private $model;
        private $httpState;
        public function __construct () {
            $this->model = new AuthModel ();
            $this->httpState = new Response ();
        }
        public function login ($json) {
            $data = json_decode ($json, true);
            if (!isset ($data ["user"]) || !isset ($data ["password"])) {
                return $this->httpState->res400 ();
            }
            $user = $data ["user"];
            $password = $data ["password"];
            $userData = $this->model->getUserData ($user);
            if ($userData) {
                $encryptedPassword = $this->model->encryptPassword ($password);
                if ($encryptedPassword === $userData ["AUTPASSWORD"]) {
                    $token = $this->model->generateToken ($userData ["AUTID"]);
                    if ($token) {
                        $stat = $this->httpState->res;
                        $stat ["msg"] = "Get token for user";
                        $stat ["data"] = [
                            "token" => $token,
                            "id" => $userData ["AUTID"],
                            "name" => $userData ["AUTNAME"],
                            "city" => $userData ["AUTCITY"],
                            "age" => $userData ["AUTAGE"],
                            "user" => $userData ["AUTNICKNAME"]
                        ];
                        return $stat;
                    } 
                    else {
                        return $this->httpState->res500 ("Internal error, we were unable to save the token");
                    }
                } 
                else {
                    return $this->httpState->res200 ("Invalid password");
                }
            } 
            else {
                return $this->httpState->res200 ("The user $user does not exist");
            }
        }
        public function register ($json) {
            $data = json_decode ($json, true);
            if (!isset ($data ["name"], $data ["city"], $data ["age"], $data ["user"], $data ["password"])) {
                return $this->httpState->res400 ();
            }
            $name = $data ["name"];
            $city = $data ["city"];
            $age = $data ["age"];
            $user = $data ["user"];
            $password = $data ["password"];
            if ($this->model->userExists ($user)) {
                return $this->httpState->res409 ();
            }
            $userId = $this->model->createUser ($name, $city, $age, $user, $password);
            if ($userId) {
                $token = $this->model->generateToken ($userId);
                if ($token) {
                    $stat = $this->httpState->res;
                    $stat ["msg"] = "Get token for user";
                    $stat ["data"] = [
                        "token" => $token,
                        "id" => $userId,
                        "name" => $name,
                        "city" => $city,
                        "age" => $age,
                        "user" => $user
                    ];
                    return $stat;
                } 
                else {
                    return $this->httpState->res500 ("Internal error, we were unable to save the token");
                }
            } 
            else {
                return $this->httpState->res500 ("Internal error, we were unable to insert user");
            }
        }
        public function getData ($id, $token) {
            $userId = (int) $id;
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token, $userId)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            if (!$this->model->getUserById ($userId)) {
                return $this->httpState->res404 ("The user with id: $userId does not exist");
            }
            $data = $this->model->getUserById ($userId);
            $stat = $this->httpState->res;
            $stat = $this->httpState->res;
            $stat ["msg"] = "Get user data";
            $stat ["data"] = transformUser ($data);
            return $stat;
        }
        public function update ($json, $token) {
            $data = json_decode ($json, true);
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token, $data ["id"])) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            if (!isset ($data ["id"], $data ["name"], $data ["city"], $data ["age"], $data ["user"], $data ["password"])) {
                return $this->httpState->res400 ();
            }
            $userId = $data ["id"];
            $name = $data ["name"];
            $city = $data ["city"];
            $age = $data ["age"];
            $user = $data ["user"];
            $password = $data ["password"];
            if (!$this->model->getUserById ($userId)) {
                return $this->httpState->res404 ("The user with id: $userId does not exist");
            }
            if ($this->model->getUserById ($userId)) {
                $this->model->updateUser ($userId, $name, $city, $age, $user, $password);
                $stat = $this->httpState->res;
                $stat ["msg"] = "User updated successfully";
                $stat ["data"] = $userId;
                return $stat;
            } 
            else {
                return $this->httpState->res500 ("Internal error, we couldn't update user");
            }
        }
        public function delete ($value, $token) {
            if (!$token) {
                return $this->httpState->res401 ();
            }
            if (!$this->model->validateToken ($token, $value)) {
                return $this->httpState->res401 ("The token sent is invalid or has expired");
            }
            if (!isset ($value)) {
                return $this->httpState->res400 ("Missing id parameter");
            }
            $userId = (int) $value;
            if (!$this->model->getUserById ($userId)) {
                return $this->httpState->res404 ("The user with id: $userId does not exist");
            }
            if ($this->model->getUserById ($userId)) {
                $this->model->deleteUser ($userId);
                $stat = $this->httpState->res;
                $stat ["msg"] = "User deleted successfully";
                $stat ["data"] = $userId;
                return $stat;
            } 
            else {
                return $this->httpState->res500 ("Internal error, we couldn't delete user");
            }
        }
    }
?>
