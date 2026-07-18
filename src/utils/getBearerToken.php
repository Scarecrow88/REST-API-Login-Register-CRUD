<?php
    function getToken ($authHeader) {
        if (preg_match ('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches [1];
        }
        return null;
    }
?>