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

$fields['sku'] = ['label' => 'SKU', 'validation' => ['required']];
$fields['name'] = ['label' => 'Name', 'validation' => ['required']];
$fields['price'] = ['label' => 'Price', 'validation' => ['required']];
$fields['productType'] = ['label' => 'options', 'validation' => ['selected']];
$fields['size']= ['label' => 'size', 'validation' =>  ['required1']];
$fields['weight'] = ['label' => 'weight', 'validation' => ['required2']];
$fields['height'] = ['label' => 'height', 'validation' => ['required3']];
$fields['length'] = ['label' => 'length', 'validation' => ['required3']];
$fields['width'] = ['label' => 'width', 'validation' =>  ['required3']];

//Instantiate post
$product = new Post($db,$table,$fields);

$post = []; // array to hold a trimmed working copy of the form data
$errors = []; // array to hold user/validation errors

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Get raw data

    $json = json_decode(file_get_contents("php://input"), true);

    // trim all the input data at once
    $post = array_map('trim', $json);

    foreach ($fields as $field => $arr) {
        if (isset($arr['validation']) && is_array($arr['validation'])) {
            foreach ($arr['validation'] as $rule) {
                switch ($rule) {
                    case 'required':
                        if ($post[$field] === '') {
                            $errors[$field] = "{$arr['label']} is required";
                        }
                        break;
                                                     
                        case 'selected':
                            if ($post[$field] == 'select') {
                                $errors[$field] = "Please select one of the provided {$arr['label']}";
                            } elseif(empty($post[$field] == 'dvd')){
                                $errors[$field] = "Please enter the required {$arr['label']}";
                            }
                            
                          break;
                        //   case 'required1':
                            
                        //   if($post[$field] === 'dvd' && $post[$field] === '') {
                        //     $errors[$field] = "Please enter the required {$arr['label']}";
                        // }
                        // break;
                        // case 'required2':

                        // if($post[$field] == 'book') {
                        //     $errors[$field] = "Please enter the required {$arr['label']}";
                        // }
                        // break;
                        // case 'required3':

                        // if($post[$field] == 'furniture') {
                        //     $errors[$field] = "Please enter the required {$arr['label']}";
                        // }
                        // break;
                            // elseif($post[$field] == 'dvd') {
                            //     $errors[$field] = "Please enter the required {$arr['label']}";
                            // } elseif($post[$field] == 'book') {
                            //     $errors[$field] = "Please enter the required {$arr['label']}";
                            // } elseif($post[$field] == 'furniture-h') {
                            //     $errors[$field] = "Please enter the required {$arr['label']}";
                            // }
                            // case 'selected1':
                            //     if ($post[$field] === '') {
                            //         $errors[$field] = "Please enter the required {$arr['label']}";
                            //     } 
                            //     break;
                            //     case 'selected2':
                            //         if ($post[$field] === '') {
                            //             $errors[$field] = "Please enter the required {$arr['label']} ";
                            //         } 
                            //         break;
                            //         case 'selected3':
                            //             if ($post[$field] === '') {
                            //                 $errors[$field] = "Please enter the required {$arr['label']}";
                            //             } 
                            //             break;
                        
                            // if (empty($post[$field] == 'book')) {
                            //     $errors[$field] = "At least one {$arr['label']} must be selected";
                            // } else {
                            //     return true;
                            // }
                            // if (empty($post[$field] == 'furniture-h')) {
                            //     $errors[$field] = "At least one {$arr['label']} must be selected";
                            // } else {
                            //     return true;
                            // }
                            // if (empty($post[$field] == 'furniture-l')) {
                            //     $errors[$field] = "At least one {$arr['label']} must be selected";
                            // } else {
                            //     return true;
                            // }
                            // if (empty($post[$field] == 'furniture-w')) {
                            //     $errors[$field] = "At least one {$arr['label']} must be selected";
                            // } else {
                            //     return true;
                            // }
                            
                        // add code for other validation rules here...

                }
            }
        }
    }

    // $product->sku = $post['sku'];
    // $product->name = $post['name'];
    // $product->price = $post['price'];
    // $product->productType = $post['productType']; 
    // $product->size = $post['size'];
    // $product->weight = $post['weight'];
    // $product->height = $post['height'];
    // $product->length = $post['length'];
    // $product->width = $post['width'];

    if (empty($errors)) {

        if (!$product->create($post)) {
            // initially, just setup a canned message for the sku column
            $errors['sku'] = "SKU is already in use";
            $errors['name'] = "Name is already in use";
            // the code to detect which of multiple columns contain duplicates would replace the above line of code
        }
    }

    // if no errors, success
    if (empty($errors)) {
        $response = [
            'message' => "Created Successfully"
        ];
    } else {
        $response = [
            'message' => implode('<br>', $errors)
        ];
    }
    echo json_encode($response);
}

    // try {

    //     $product->create();
        
    //     $response = [
    //         'message' => "Created Successfully",
    //             'status' => 200
    //     ];
    //         echo json_encode($response);
        
    //     } catch (\Throwable $e) {

    //         $query = "SELECT DISTINCT sku, name FROM ' . $this->table . '";
    //         $stmt = $this->conn->prepare($query);
    //         // $stmt->execute(['sku', 'name']);

    //         if($stmt->execute(['sku']) == 1062) {
    //             $errors['sku'] = 'sku already in use';
    //         } else if ($stmt->execute(['name']) == 1062) {

    //             $errors['name'] = 'name already in use';
              
    //         } else {
    //             $response = [
    //                 'message' => $e->getMessage()
    //             ];
    //             echo json_encode($response);
    //         }

            
    //     }


//Create 
