<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, true');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require '../../config/database.php';
require '../../models/post.php';

//Instantiate db

$database = new Database();
$db = $database->connect();

// define the $table and $fields for the Create operation
$table = 'skandi';
$fields = [];

$fields['sku'] = ['label' => 'SKU','validation' => ['required']];
$fields['name'] = ['label' => 'Name','validation' => ['required']];
$fields['price'] = ['label' => 'Price','validation' => ['required']];
$fields['productType'] = ['label' => 'Product Type','validation' => ['required'],
            'options' => ['dvd', 'book', 'furniture']];
$fields['size'] = ['label' => 'size','validation' => ['required']];
$fields['weight'] = ['label' => 'weight','validation' => ['required']];
$fields['height'] = ['label' => 'height','validation' => ['required']];
$fields['length'] = ['label' => 'length','validation' => ['required']];
$fields['width'] = ['label' => 'width','validation' => ['required']];


//Instantiate post
$product = new Post($db, $table, $fields);

$post = []; // array to hold a trimmed working copy of the form data
$errors = []; // array to hold user/validation errors

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Get raw data

    $json = json_decode(file_get_contents("php://input"), true);

    // trim all the input data at once
    $post = array_map('trim', $json);


    foreach ($fields as $field => $arr) {
        if (isset($arr['validation']) && is_array($arr['validation'])) {
            if (($field === 'size' && $post['productType'] !== 'dvd') ||
            ($field === 'weight' && $post['productType'] !== 'book') ||
            ($field === 'height' && $post['productType'] !== 'furniture') ||
            ($field === 'length' && $post['productType'] !== 'furniture') ||
            ($field === 'width' && $post['productType'] !== 'furniture')) {
            continue;
        }
            foreach ($arr['validation'] as $rule) {
                switch ($rule) {
                    case 'required':
                        if (!$post[$field]) {
                            $errors[$field] = "{$arr['label']} is required";
                        }
                        
                        // check if field has a list of valid options
                        if (isset($arr['options']) && is_array($arr['options'])) {
                            // check if the selected value is in the list of valid options
                            if (empty($post[$field])) {
                                $errors[$field] = "{$arr['label']} must be one of the following: " . implode(', ', $arr['options']);
                            }
                        }
                        break;
                }
            }
        }
    }

    // if (empty($errors)) {
    //     $result = $product->create($post);
    //     if (isset($result) && isset($result['errors'])) {
    //         $errors = $result['errors'];
    //     }
    // }
    
    // if no errors, success
    if (empty($errors)) {
        $result = $product->create($post);
        if (isset($result['success']) && $result['success'] === true) {
            $response = [
                'success' => true,
                'message' => "Created Successfully"
            ];
        } else {
            $response = [
                'success' => false,
                'errors' => $result['errors']
            ];
        }
    } else {
        $response = [
            'success' => false,
            'errors' => $errors
        ];
    }
    echo json_encode($response);
}