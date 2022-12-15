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

$fields = [
    'sku' => [
        'label' => 'SKU',
        'validation' => ['required']
    ],
    'name' => [
        'label' => 'Name',
        'validation' => ['required']
    ],
    'price' => [
        'label' => 'Price',
        'validation' => ['required']
    ],
    'productType' => [
        'label' => 'Product Type',
        'validation' => ['selected'],
        'options' => ['dvd', 'book', 'furniture']
    ],
    'dvd' => [
        'label' => 'size',
        'validation' => ['required']
    ],
    'book' => [
        'label' => 'weight',
        'validation' => ['required']
    ],
    'furniture' => [
        'height' => [
            'label' => 'height', 'validation' => ['required']
        ],
        'length' => [
            'label' => 'length', 'validation' => ['required']
        ],
        'width' => [
            'label' => 'width', 'validation' => ['required']
        ]
    ]

];

//Instantiate post
$product = new Post($db, $table, $fields);

$post = []; // array to hold a trimmed working copy of the form data
$errors = []; // array to hold user/validation errors

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //Get raw data

    $json = json_decode(file_get_contents("php://input"), true);

    // trim all the input data at once
    $post = array_map('trim', $json);

    $fieldsToValidate = [
        'sku' => $fields['sku'],
        'name' => $fields['name'],
        'price' => $fields['price'],
        'productType' => $fields['productType']
    ];

    if (isset($post['productType']) && !empty($post['productType'])) {
        // check if the selected value is in the list of valid options
        if (in_array($post['productType'], $fields['productType']['options'])) {
            // add the additional fields for the selected product type to the list of fields to validate
            switch ($post['productType']) {
                case 'dvd':
                    $fieldsToValidate = array_merge($fieldsToValidate, [
                        $fields['dvd']['label'] => $fields['dvd']
                    ]);
                    break;
                case 'book':
                    $fieldsToValidate = array_merge($fieldsToValidate, [
                        $fields['book']['label'] => $fields['book']
                    ]);
                    break;
                case 'furniture':
                    $fieldsToValidate = array_merge($fieldsToValidate, [
                        'height' => $fields['furniture']['height'],
                        'length' => $fields['furniture']['length'],
                        'width' => $fields['furniture']['width'],
                    ]);
                    break;
            }
        } else {
            // set to empty array if productType is not one of the expected values
            $fieldsToValidate = [];
        }
    }


    if (empty($fieldsToValidate)) {
        // set $fieldsToValidate to the default fields to validate
        $fieldsToValidate = [
            'sku' => $fields['sku'],
            'name' => $fields['name'],
            'price' => $fields['price']
        ];
    }

    foreach ($fieldsToValidate as $field => $arr) {
        if (isset($arr['validation']) && is_array($arr['validation'])) {
            foreach ($arr['validation'] as $rule) {
                switch ($rule) {
                    case 'required':
                        if (!$post[$field]) {
                            $errors[$field] = "{$arr['label']} is required";
                        }
                        break;

                    case 'selected':
                        // check if field has a list of valid options
                        if (isset($arr['options']) && is_array($arr['options'])) {
                            // check if the selected value is in the list of valid options
                            if (in_array($post[$field], $arr['options'])) {
                                // check if a value is entered for the selected option
                                if (!$post[$field]) {
                                    $errors[$field] = "{$arr['label']} must be one of the following: " . implode(', ', $arr['options']);
                                }
                            }
                        }
                        break;
                        foreach ($fields['furniture'] as $field => $arr) {
                            if (isset($arr['validation']) && is_array($arr['validation'])) {
                                foreach ($arr['validation'] as $rule) {
                                    switch ($rule) {
                                        case 'required':
                                            if (!$post[$field]) {
                                                $errors[$field] = "{$arr['label']} is required";
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                }
            }
        }
    }

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
