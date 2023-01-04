<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


require '../../config/database.php';
require '../../models/post.php';

//Instantiate db

$database = new Database();
$db = $database->connect();
$table = 'skandi';
$fields = [];

//Instantiate post
$product = new Post($db,$table,$fields);
$json = json_decode(file_get_contents("php://input"), true);


$product->id = $json['id'];


try {
  $response = $product->delete($product->id);
  echo $response;
} catch (\Throwable $e) {
  echo "Error occurred in delete method: " . $e->getMessage();
}







