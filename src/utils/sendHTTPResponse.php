<?php
    function sendJsonResponse ($datArr) {
        if (isset ($datArr ["data"] ["error_id"])) {
            http_response_code ($datArr ["data"] ["error_id"]);
        } 
        else {
            http_response_code ((int) $datArr ["status"]);
        }
        echo json_encode ($datArr);
    }
?>