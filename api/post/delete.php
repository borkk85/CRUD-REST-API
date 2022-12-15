<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/x-www-form-urlencoded');
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
$post = [];

$id = explode(',', $_GET['id']);


try {
  $response = $product->delete($id);
  echo $response;
} catch (\Throwable $e) {
  echo "Error occurred in delete method: " . $e->getMessage();
}







