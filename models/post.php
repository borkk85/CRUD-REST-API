<?php 

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

    public function __construct($db) {
        $this->conn = $db; 
        
    }

    
    //Get Posts
    public function read() {
        $query = 'SELECT 
            p.id as id, 
            p.sku,
            p.name, 
            p.price, 
            p.productType,
            p.size, 
            p.weight, 
            p.height, 
            p.length, 
            p.width FROM '. $this->table .' p  ORDER BY p.id DESC'; 
    
    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt;
}

   // Create Product

   public function create ($data) {

    $set_terms = [];
    $params = [];

    foreach(array_keys($this->fields) as $field) {
        $set_terms[] = "`$field`=?";
			$params[] = $data[$field];
    }

 
    $query = "INSERT INTO `$this->table` SET " . implode(',',$set_terms);
    $stmt = $this->conn->prepare($query);

    try {
        
        $stmt->execute($params);
        
        return true;

    
        // $stmt->bindParam(':sku', $this->sku);
        // $stmt->bindParam(':name', $this->name);
        // $stmt->bindParam(':price', $this->price);
        // $stmt->bindParam(':productType', $this->productType);
        // $stmt->bindParam(':size', $this->size);
        // $stmt->bindParam(':weight', $this->weight);
        // $stmt->bindParam(':height', $this->height);
        // $stmt->bindParam(':length', $this->length);
        // $stmt->bindParam(':width', $this->width);
        
        
    } catch (PDOException $e) {
       
        if($e->errorInfo[1] == 1062) // duplicate key error number
        {
            return false;
        }
        throw $e; // re-throw the pdoexception if not handled by this logic
        
    }
    }
  

   public function delete(){

    // $ids = array($this->id);
    // $inQuery = implode(",", array_fill(0, count($ids)-1, "?"));
    
    // $query = 'DELETE FROM  . $this->table . WHERE id IN (' . $inQuery .')'; 
    // $stmt = $this->conn->prepare($query);
    // foreach($ids as $k => $this->id) 
    //  $stmt->bindParam(($k + 1), $this->id, PDO::PARAM_STR);
    
    // if($stmt->execute()) {
    //     return true;
    
    // } else {
    //     // printf("Error: %s.\n", $stmt->error);
    //     ini_set('display_errors',1);
    //     return false;
    // }
        
   // Create query
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

          try {
                   // Prepare statement
                   $stmt->execute();

                   return true;
          // Clean data
        //   $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data

          // Execute query
         
        } catch(\Throwable $e) {
            $e = [
                'message' => $e->getMessage()
            ];
            echo json_encode($e);
        }
    }
 
}