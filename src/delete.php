<?php
    require_once __DIR__ . '/controllers/AuthController.class.php';
    require_once __DIR__ . '/utils/getBearerToken.php';
    require_once __DIR__ . '/utils/sendHTTPResponse.php';
    require_once __DIR__ . '/web/Response.php';
    header ("Access-Control-Allow-Origin: *");
    header ("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header ("Access-Control-Allow-Headers: Content-Type, Authorization");
    header ("Access-Control-Allow-Credentials: true");
    header ('Content-Type: application/json; charset=UTF-8');
    $stat = new Response;
    $auth = new AuthController;
    $headers = getallheaders ();
    $authHeader = $headers ['Authorization'] ?? '';
    $token = getToken ($authHeader);
    switch ($_SERVER ["REQUEST_METHOD"]) {
        case "OPTIONS":
            http_response_code (204);
            exit ();
        case "DELETE":
            if (isset ($_GET ["id"])) {
                $itemId = $_GET ["id"];
                $dataArray = $auth->delete ($itemId, $token);
                sendJsonResponse ($dataArray);
            } 
            else {
                sendJsonResponse ($stat->res400 ("Missing id parameter"));
            }
            break;
        default:
            http_response_code (405);
            echo json_encode ($stat->res405 ());
            break;
    }
?>