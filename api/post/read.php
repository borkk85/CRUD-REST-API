<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, true');
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

//Product query

$result = $product->read();

$num = $result->rowCount();

if($num>0) {

    $post_arr = array();
    // $post_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = array(
            'id' => $id,
            'sku' => $sku,
            'name' => $name,
            'price' => $price,
            'productType' => $productType,
            'size' => $size,
            'weight' => $weight,
            'height' => $height,
            'length' => $length,
            'width' => $width
        );

        //Push to 'data'

        array_push($post_arr, $post_item);
        // array_push($post_arr['data'], $post_item);

    }

    

    //Turn to JSON & output
     echo json_encode($post_arr);

     
} else {
    //No post

    echo json_encode(
        array('message' => 'No Posts Found')
    );
}