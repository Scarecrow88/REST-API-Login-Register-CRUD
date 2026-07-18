<?php
    require_once __DIR__ . '/../controllers/TokenController.php';
    $token = new TokenController;
    $currentDate = date ("Y-m-d H:i:s");
    echo $token->updateToken ($currentDate);
?>