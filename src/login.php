<?php
    require_once __DIR__ . '/controllers/AuthController.class.php';
    require_once __DIR__ . '/utils/getBearerToken.php';
    require_once __DIR__ . '/utils/sendHTTPResponse.php';
    require_once __DIR__ . '/web/Response.php';
    header ("Access-Control-Allow-Origin: *");
    // Permitir los métodos HTTP que el cliente necesita
    header ("Access-Control-Allow-Methods: POST, OPTIONS");
    // Permitir los encabezados personalizados que el cliente puede enviar
    header ("Access-Control-Allow-Headers: Content-Type, Authorization");
    // Opcional: Permitir que las credenciales (cookies, etc.) se incluyan
    header ("Access-Control-Allow-Credentials: true");
    header ('Content-Type: application/json; charset=UTF-8');
    $stat = new Response;
    $auth = new AuthController;
    switch ($_SERVER ["REQUEST_METHOD"]) {
        case "OPTIONS":
            http_response_code (204);
            exit ();
        case "POST":
            $postBody = file_get_contents ("php://input");
            $dataArray = $auth->login ($postBody);
            sendJsonResponse ($dataArray);
            break;
        default:
            sendJsonResponse ($stat->res405 ());
            break;
    }
?>