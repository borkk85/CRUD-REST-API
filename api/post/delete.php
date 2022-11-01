<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


require '../../config/database.php';
require '../../models/post.php';

//Instantiate db

$database = new Database();
$db = $database->connect();


//Instantiate post
$product = new Post($db);



//Get raw data

$json = json_decode(file_get_contents("php://input"), true);

// $data = array_map('trim', $json);

$product->id = $json->id;

// $product->id = isset($json['id']) ? count($json['id']) : '';

var_dump($json);


try {

    $product->delete($ids);
    $response = [
        'message' => "Created Successfully",
            'status' => 200
    ];
        echo json_encode($response);
    
    } catch (\Throwable $e) {
        $response = [
            'message' => $e->getMessage()
        ];
        echo json_encode($response);
    }
