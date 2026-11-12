<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();
$auth_token = isset($headers['Authorization']) ? $headers['Authorization'] : 'No Token Provided';

$raw_input = file_get_contents("php://input");
$data = json_decode($raw_input, true);

$response = [
    "method" => $method,
    "authorization" => $auth_token,
    "data" => $data
];

switch ($method) {
    case 'GET':
        http_response_code(200);
        $response["status"] = "success";
        $response["message"] = "GET request processed.";
        break;
        
    case 'POST':
        if (!empty($data)) {
            http_response_code(201);
            $response["status"] = "success";
            $response["message"] = "POST request processed. Data created.";
        } else {
            http_response_code(400);
            $response["status"] = "error";
            $response["message"] = "Missing JSON body.";
        }
        break;

    case 'PUT':
        http_response_code(200);
        $response["status"] = "success";
        $response["message"] = "PUT request processed.";
        break;

    case 'DELETE':
        http_response_code(200);
        $response["status"] = "success";
        $response["message"] = "DELETE request processed.";
        break;

    default:
        http_response_code(405);
        $response["status"] = "error";
        $response["message"] = "Method Not Allowed.";
        break;
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>