<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Post {
    //DB
    private $conn;
    private $table = 'skandi';

    //Post Properties
    public $fields; 
    public $id;
    public $sku;
    public $name;
    public $price;
    public $productType;
    public $size;
    public $weight;
    public $height;
    public $length;
    public $width;

    //Constructor 

    public function __construct($db,$table,$fields) {
        $this->conn = $db; 
        $this->table = $table;
        $this->fields = $fields;
    }

    
    //Get Posts
    public function read() {
        $query = "SELECT 
            p.id as id, 
            p.sku,
            p.name, 
            p.price, 
            p.productType,
            p.size, 
            p.weight, 
            p.height, 
            p.length, 
            p.width FROM `$this->table` p  ORDER BY p.id DESC"; 
    
    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
}

   // Create Product

   public function create ($data) {

    $set_terms = [];
    $params = [];

    foreach(array_keys($this->fields) as $field) {
        if(!empty($data[$field])) {
            $set_terms[] = "`$field`=?";
            $params[] = $data[$field];
        }
    }
    $params = array_values($params);
 
    $query = "INSERT INTO `$this->table` SET " . implode(',',$set_terms);

    $stmt = $this->conn->prepare($query);

    try {
        $stmt->execute($params);
        return ["success" => true];
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $errors = [];
            if (stripos($e->errorInfo[2] , 'sku') !== false) {
                $errors['sku'] = "SKU is already in use. ";
            }
            if (stripos($e->errorInfo[2] , 'name') !== false) {
                $errors['name'] = "Name is already in use. ";
            }
            return ["success" => false, "errors" => $errors];
        } else {
            return ["success" => false, "errors" => ["sku" => $e->getMessage(), "name" => $e->getMessage()]];
        }
    }
    }
  


public function delete($ids) {
  // Prepare the DELETE statement
  $query = "DELETE FROM `$this->table` WHERE id = ?";
  $stmt = $this->conn->prepare($query);

  // Initialize the rows_deleted variable
  $rows_deleted = 0;

  // Loop through the IDs array
  foreach ($ids as $id) {
    // Bind the id value to the placeholder
    $stmt->bindValue(1, $id, PDO::PARAM_INT);

    // Execute the DELETE statement
    $stmt->execute();

    // Increment the rows_deleted variable by the number of rows affected
    $rows_deleted += $stmt->rowCount();
  }

  // Return the rows_deleted value as a JSON string
  return json_encode(['rows_deleted' => $rows_deleted]);
}
 
}