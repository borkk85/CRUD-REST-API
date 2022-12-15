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
    if (empty($set_terms)) {
        // Return an error message to the user
        throw new PDOException("No values were provided for the fields");
    }
    $stmt = $this->conn->prepare($query);

    try {
        
        $stmt->execute($params);
        
        return true;
        
    } catch (PDOException $e) {
       
        if($e->errorInfo[1] == 1062) // duplicate key error number
        {
            return false;
        }
        throw $e; // re-throw the pdoexception if not handled by this logic
        
    }
    }
  

//    public function delete(){

//     // $ids = array($this->id);
//     // $inQuery = implode(",", array_fill(0, count($ids)-1, "?"));
    
//     // $query = 'DELETE FROM  . $this->table . WHERE id IN (' . $inQuery .')'; 
//     // $stmt = $this->conn->prepare($query);
//     // foreach($ids as $k => $this->id) 
//     //  $stmt->bindParam(($k + 1), $this->id, PDO::PARAM_STR);
    
//     // if($stmt->execute()) {
//     //     return true;
    
//     // } else {
//     //     // printf("Error: %s.\n", $stmt->error);
//     //     ini_set('display_errors',1);
//     //     return false;
//     // }
        
//    // Create query
//           // Create query
//         //   $input = json_decode(file_get_contents('php://input'));

//           $query = "DELETE FROM  `$this->table` WHERE id = :id";
//           $stmt = $this->conn->prepare($query);
//           $this->id = htmlspecialchars(strip_tags($this->id));
//           $stmt->bindParam(':id', $this->id);

//           try {
//                    // Prepare statement
//                    $stmt->execute();

//                    return json_encode(['rows_deleted' => $stmt->rowCount()]);
//           // Clean data
//         //   $this->id = htmlspecialchars(strip_tags($this->id));

//           // Bind data

//           // Execute query
         
//         } catch(\Throwable $e) {
//             $e = [
//                 'message' => $e->getMessage()
//             ];
//             echo json_encode($e);
//         }

//     }

public function delete($id) {
    // Convert the $id array into a comma-separated string
    $id = implode(',', $id);
  
    $query = "DELETE FROM  `$this->table` WHERE id IN ($id)";
    $stmt = $this->conn->prepare($query);
  
    try {
      // Prepare statement
      $result = $stmt->execute();
      return json_encode(['rows_deleted' => $stmt->rowCount()]);
    } catch (\Throwable $e) {
      $e = [
        'message' => $e->getMessage()
      ];
      echo json_encode($e);
    }
  }
 
}