<?php
    require_once __DIR__ . '/controllers/ItemController.class.php';
    require_once __DIR__ . '/utils/getBearerToken.php';
    require_once __DIR__ . '/utils/sendHTTPResponse.php';
    require_once __DIR__ . '/web/Response.php';
    header ("Access-Control-Allow-Origin: *");
    header ("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header ("Access-Control-Allow-Headers: Content-Type, Authorization");
    header ("Access-Control-Allow-Credentials: true");
    header ('Content-Type: application/json; charset=UTF-8');
    $stat = new Response;
    $items = new ItemsController;
    $headers = getallheaders ();
    $authHeader = $headers ['Authorization'] ?? '';
    $token = getToken ($authHeader);
    switch ($_SERVER ["REQUEST_METHOD"]) {
        case "OPTIONS":
            http_response_code (204);
            exit ();
        case "GET": 
            if (isset ($_GET ["id"])) {
                $itemId = $_GET ["id"];
                $dataArray = $items->getOne ($itemId, $token);
            } 
            else {
                $page = isset ($_GET ["page"]) ? (int) $_GET ["page"] : 1;
                $dataArray = $items->getAll ($page, $token);
            }
            sendJsonResponse ($dataArray);
            break;
        case "POST":
            $postBody = file_get_contents ("php://input");
            $dataArray = $items->post ($postBody, $token);
            sendJsonResponse ($dataArray);
            break;
        case "PUT":
            $putBody = file_get_contents ("php://input");
            $dataArray = $items->put ($putBody, $token);
            sendJsonResponse ($dataArray);
            break;
        case "DELETE":
            $deleteBody = file_get_contents ("php://input");
            $dataArray = $items->delete ($deleteBody, $token);
            sendJsonResponse ($dataArray);
            break;
        default:
            sendJsonResponse ($stat->res405 ());
            break;
    }
?>
