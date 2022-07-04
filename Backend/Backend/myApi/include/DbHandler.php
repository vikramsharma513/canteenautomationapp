<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createCustomer($name, $email, $password, $phoneNum) {
        require_once 'PassHash.php'; 
        $response = array();

        // First check if user already existed in db
        if (!$this->isCustomerExists($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $apikey = $this->generateApiKey();

            // insert query
            $stmt = $this->conn->prepare("INSERT INTO customer(name, email, password, phoneNum, apikey) values(?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $password_hash, $phoneNum, $apikey);

            $result = $stmt->execute();

            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }

        return $response;
    }

    public function createAdmin($name,$email, $password ) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isAdminExists($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $apikey = $this->generateApiKey();

            // insert query
            $stmt = $this->conn->prepare("INSERT INTO admin( name, email, password, apikey) values(?,?,?,?)");
            $stmt->bind_param("ssss", $name,$email, $password_hash, $apikey);

            $result = $stmt->execute();

            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
        // User with same email already existed in the db
        return USER_ALREADY_EXISTED;
        }
        return $response;
    }
    
     /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkCustomerLogin($email, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password FROM customer WHERE email = ?");
    
        $stmt->bind_param("s", $email);
    
        $stmt->execute();
    
        $stmt->bind_result($password_hash);
    
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password
    
            $stmt->fetch();
    
            $stmt->close();
    
            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();
    
            // user not existed with the email
            return FALSE;
        }
    }

         
    public function checkAdminLogin($email, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password FROM admin WHERE email = ?");
    
        $stmt->bind_param("s", $email);
    
        $stmt->execute();
    
        $stmt->bind_result($password_hash);
    
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password
    
            $stmt->fetch();
    
            $stmt->close();
    
            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();
    
            // user not existed with the email
            return FALSE;
        }
    }

    
    ////////////
    function getDirectory(){
            $dir = "../../../profilePicture/";
        
            $year = date("Y");
            $month = date("m");
            $yearDirectory = $dir . $year;
            $folderDirectory = $dir . $year . "/" . $month ."/";
            if (file_exists($yearDirectory)) {
                if (file_exists($folderDirectory) == false) {
                    mkdir($folderDirectory, 0777);
                    return $folderDirectory ;
                }
            } else {
                mkdir($yearDirectory, 0777);
                mkdir($folderDirectory, 0777);
                return $folderDirectory ;
            }
            return $folderDirectory ;
        }
    
    function getFoodDirectory()
    {
        $dir = "../../../Backend/adminSection/FoodImage/";
    
        $year = date("Y");
        $month = date("m");
        $yearDirectory = $dir . $year;
        $folderDirectory = $dir . $year . "/" . $month ."/";
        if (file_exists($yearDirectory)) {
            if (file_exists($folderDirectory) == false) {
                mkdir($folderDirectory, 0777);
                return $folderDirectory ;
            }
        } else {
            mkdir($yearDirectory, 0777);
            mkdir($folderDirectory, 0777);
            return $folderDirectory ;
        }
        return $folderDirectory ;
    }
    
    
    public function editUser($customerId, $full_name, $password, $email_id, $phone_number, $fileSize, $fileTmpName, $fileType, $fileExtension , $filename ,$finalImageLink, $has_pp){
            if($has_pp){
            $currentDir = getcwd();
            $errors = [];
            $uploadDirectory ="/". $this->getDirectory();
            $uploadPath = $currentDir . $uploadDirectory . basename($filename);
            $fileExtensions = ['jpeg', 'jpg', 'png', 'JPG', 'PNG', 'JPEG'];
        
            if (!in_array($fileExtension, $fileExtensions)) {
                return 2;
            } elseif ($fileSize > 14256000) {
                return 3;
            } else {
        
                if (file_exists($uploadPath)) {
        
                    unlink($uploadPath);
        
                }
        
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    $success=$this->updateUserInfo($customerId, $full_name, $password, $email_id, $phone_number, $finalImageLink);
                    if($success){
                        return 1;
                        
                    }else{
                        return 2;
                        
                    }
                } else {
                    return 4;
                }
            }
            
            }
            else{
                $success=$this->updateUserInfo($customerId, $full_name, $password, $email_id, $phone_number, $finalImageLink);
                if($success){
                    return 1;
                        
                }else{
                    return 2;
                        
                }
            }
            
        }
    
    
    public function updateUserInfo($customerId, $full_name, $password, $email_id, $phone_number, $imageLink){
            require_once 'PassHash.php';
            $finalPassword=null;
            
            if(empty($password)){
                $stmt = $this->conn->prepare("SELECT password FROM customer WHERE customerId = ?");
    
                $stmt->bind_param("i", $customerId);
    
                $stmt->execute();
    
                $stmt->bind_result($password_hash);
    
                $stmt->store_result();
                $stmt->fetch();
    
                $stmt->close();
                
                $finalPassword=$password_hash;
    
            }else{
                $finalPassword = PassHash::hash($password);
            }
            
            $stmt = $this->conn->prepare( "UPDATE customer set name=?,  password=?, email=?, phoneNum=?, profile_pic=? where customerId=?");
            $stmt->bind_param("sssssi", $full_name, $finalPassword, $email_id, $phone_number, $imageLink, $customerId);
            $result = $stmt->execute();
            $stmt->close();      
        
            if ($result) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            
        }

    
    ////////////////
    
    public function getCustomerById($customerId) {
             $stmt = $this->conn->prepare("SELECT name, email, profile_pic, phoneNum FROM customer WHERE customerId = ?");
                $stmt->bind_param("i", $customerId);
                if ($stmt->execute()) {
                    // $user = $stmt->get_result()->fetch_assoc();
                    $stmt->bind_result($name, $email, $profile_pic, $phoneNum);
                    $stmt->fetch();
                    $customer = array();
                    $customer["name"] = $name;
                    $customer["email"] = $email;
                    $customer["profile_pic"] = $profile_pic;
                    $customer["phoneNum"] = $phoneNum;
                    $stmt->close();
                    return $customer;
                } else {
                    return NULL;
                }
            
        }
    
    ///////////
    
    public function editAdminUser($adminId, $full_name, $password, $finalEmailId){
            require_once 'PassHash.php';
            $finalPassword=null;
            
            if(empty($password)){
                $stmt = $this->conn->prepare("SELECT password FROM admin WHERE adminId = ?");
    
                $stmt->bind_param("i", $adminId);
    
                $stmt->execute();
    
                $stmt->bind_result($password_hash);
    
                $stmt->store_result();
                $stmt->fetch();
    
                $stmt->close();
                
                $finalPassword=$password_hash;
    
            }else{
                $finalPassword = PassHash::hash($password);
            }
            
            $stmt = $this->conn->prepare( "UPDATE admin set name=?,  password=?, email=? where adminId=?");
            $stmt->bind_param("sssi", $full_name, $finalPassword, $finalEmailId,$adminId);
            $result = $stmt->execute();
            $stmt->close();      
        
            if ($result) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            
        }
    
     public function getAdminById($adminId) {
             $stmt = $this->conn->prepare("SELECT name, email FROM admin WHERE adminId = ?");
                $stmt->bind_param("i", $adminId);
                if ($stmt->execute()) {
                    // $user = $stmt->get_result()->fetch_assoc();
                    $stmt->bind_result($name, $email);
                    $stmt->fetch();
                    $admin = array();
                    $admin["name"] = $name;
                    $admin["email"] = $email;
                  
                    $stmt->close();
                    return $admin;
                } else {
                    return NULL;
                }
            
        }
        ////////////

    public function orderBy($customerId, $pay_method,$pay_status){
        
    $cart = $this->getCart($customerId);
    
    if($cart == 0)
      return 10;
    $stmt = $this->conn->prepare("INSERT INTO orders (customerId,pay_method,pay_status) values(?,?,?)");
    $stmt->bind_param("isi", $customerId,$pay_method,$pay_status);
    $result = $stmt->execute();
    $stmt->close();
    $last_id = mysqli_insert_id($this->conn);
    foreach($cart as $foodlist){
        $foodId = $foodlist["foodId"];
        $quantity = $foodlist["quantity"];
        $price = $foodlist["price"];
        $stmt = $this->conn->prepare("INSERT INTO `order_details` (`order_id`, `foodId`, `quantity`, `price`) values( ?, ?, ?, ?)");
        $stmt->bind_param("iiii", $last_id, $foodId, $quantity, $price);
        $result = $stmt->execute();
        $stmt->close();
        $q=$this -> getQuantityFromFoodlist($foodId);
        $qty=$q-$quantity;
        $stmt2 = $this->conn->prepare("UPDATE foodlist SET quantity=? WHERE foodId=?");
        $stmt2->bind_param("ii", $qty, $foodId);
        $res = $stmt2->execute();
        $stmt2->close();
    }
    $this->delete_cart_items($customerId);
  return 1;
  
 }
 
    public function getQuantityFromFoodlist($foodId) {
        $stmt = $this->conn->prepare("SELECT quantity FROM foodlist WHERE foodId = ?");
        $stmt->bind_param("i", $foodId);
        if ($stmt->execute()) {
            $stmt->bind_result($q);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $q;
        } else {
            return NULL;
        }
    }
 
  
    public function delete_cart_items($customerId){
     
    $stmt = $this->conn->prepare( "DELETE FROM `cart` WHERE `cart`.`customerId` = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->close();

 }
    
    //update quantity to cart function
    public function updateQuantity($user,$item, $q, $price){
        $qty=$this -> getQuantityFromFoodlist($item);
        if($qty>=$q){
            $stmt = $this->conn->prepare( "UPDATE cart set quantity=?, price=? where customerId=? AND foodId=?");
            $stmt->bind_param("iiii", $q, $price, $user, $item);
            $result = $stmt->execute();
            $stmt->close();      
        
            if ($result) {
                    // successfully updated
                    return TRUE;
                } else {
                    // Failed to update
                    return FALSE;
                }
        }
        
        

    }
    
    
    public function deleteFromCart($customerId, $foodId){
        
        $stmt2 = $this->conn->prepare("SELECT * from cart WHERE customerId = ? AND foodId= ?");
        $stmt2->bind_param("ii", $customerId, $foodId);
        $stmt2->execute();   
        $result2 = $stmt2->get_result();
        $full_rows = array();
            if ($result2-> num_rows>0) {
                $stmt = $this->conn->prepare("DELETE from cart WHERE customerId = ? AND foodId= ?");
                $stmt->bind_param("ii", $customerId, $foodId);
                $result=$stmt->execute();
                return TRUE;
            } else {
                return FALSE;
            }
    
        }
   
    
    //show order history
    public function showOrders($customerId){
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE customerId='$customerId'");
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
        }
    
    //show order history to admin
    public function getOrders(){
            $stmt = $this->conn->prepare("SELECT o.order_id, c.customerId,c.name, c.phoneNum, o.order_date, o.pay_method, o.status, o.pay_status from orders o, customer c WHERE o.customerId=c.customerId");
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
        }
    
        
     //show each orders
    public function showeach($order_id){
        $stmt = $this->conn->prepare("SELECT order_details.id, order_details.order_id, foodlist.image_url, foodlist.foodName, order_details.quantity, order_details.price FROM
        order_details INNER JOIN foodlist on order_details.foodID=foodlist.foodID WHERE order_details.order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();   
        $result = $stmt->get_result();
        $full_rows = array();
    
        if ($result->num_rows > 0) {
            $stmt->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $stmt->close();
            return 0;
        }
    
    }
    
     //update order status
    public function updateOrderStatus($order_id,$status){
        $stmt = $this->conn->prepare("UPDATE orders set status=? WHERE order_id = ?");
        $stmt->bind_param("ii",$status, $order_id);
        $result = $stmt->execute();
        $stmt->close();      
    
        if ($result) {
                // successfully updated
                return TRUE;
            } else {
                // Failed to update
                return FALSE;
            }
    }
    
    
     //update pay status
    public function updatePayStatus($order_id,$paystatus){
        $stmt = $this->conn->prepare("UPDATE orders set pay_status=? WHERE order_id = ?");
        $stmt->bind_param("ii",$paystatus, $order_id);
        $result = $stmt->execute();
        $stmt->close();      
    
        if ($result) {
                // successfully updated
                return TRUE;
            } else {
                // Failed to update
                return FALSE;
            }
    
    }
    
    //show categories;
    public function showCategories(){
            $stmt = $this->conn->prepare("SELECT * from category");
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
        }

    //show customers;
    public function showCustomers(){
            $stmt = $this->conn->prepare("SELECT * from customer");
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
        }

    //show items;
    public function showItems(){
            $stmt = $this->conn->prepare(
                "SELECT f.foodId, f.foodName, f.foodDesc, f.price, f.quantity, f.image_url, c.categoryName from foodlist f, category c WHERE f.categoryId=c.categoryId");
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
            
            
        }
        
   

    //add to cart function
    public function addtocart($user,$item, $q, $price){
        
         $stmt = $this->conn->prepare( "INSERT INTO cart ( foodId, customerId, quantity, price) VALUES ( ?, ?, ?,?)");
        $stmt->bind_param("iiii",$item,$user, $q, $price);
        $result = $stmt->execute();
        $stmt->close();      
    
        if ($result) {
                // User successfully inserted
                return TRUE;
            } else {
                // Failed to create user
                return FALSE;
            }
    }
    
    ///////////////
    
       //get cart values
    public function getCart($customerId){
            $stmt = $this->conn->prepare("SELECT * from cart WHERE customerId = ?");
            $stmt->bind_param("i", $customerId);
            $stmt->execute();   
            $result = $stmt->get_result();
            $full_rows = array();
        
    
            if ($result->num_rows > 0) {
                $stmt->close();
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $stmt->close();
                return 0;
            }
        }
        
    //get items from cart
    public function getCartProducts($out){
        if($out  == null)
     return 0;
        $response = array();
        foreach($out as $foodlist){
            $each_product =   $this -> getProductFromId($foodlist['foodId']);
            if($each_product != 0)
            $each_product['quantity'] = $foodlist['quantity'];
            array_push($response, $each_product);
        }
    
        if ($response) {
            return $response;
        } else {
            return 0;
        }
    }
    
    //get food items by foodId
    public function getProductFromId($foodId){
        $stmt = $this->conn->prepare("SELECT * from foodlist WHERE foodId = ?");
        $stmt->bind_param("i", $foodId);
        $stmt->execute();   
        $result = $stmt->get_result();
        $full_rows = array();
    
        if ($result->num_rows > 0) {
            $stmt->close();
            return $result->fetch_all(MYSQLI_ASSOC)[0];
        } else {
            $stmt->close();
            return 0;
        }
    
    }
    //////////


    public function addCategory($category_name,$description) {
        $stmt = $this->conn->prepare("INSERT INTO category(categoryName, categoryDesc) values(?, ?)");
        $stmt->bind_param("ss", $category_name,$description);
        $result = $stmt->execute();
        $stmt->close();      

        if ($result) {
                // User successfully inserted
                return TRUE;
            } else {
                // Failed to create user
                return FALSE;
            }
    }
    
    public function editCategory($category_id,$category_name,$description) {
        $stmt = $this->conn->prepare("UPDATE category set categoryName=?, categoryDesc=? WHERE categoryId=?");
        $stmt->bind_param("ssi", $category_name,$description,$category_id);
        $result = $stmt->execute();
        $stmt->close();      

        if ($result) {
                // User successfully inserted
                return TRUE;
            } else {
                // Failed to create user
                return FALSE;
            }
    }
    
    public function delCategory($category_id) {
    $stmt = $this->conn->prepare("DELETE from category WHERE categoryId=?");
    $stmt->bind_param("i", $category_id);
    $result = $stmt->execute();
    $stmt->close();      

    if ($result) {
            // User successfully inserted
            return TRUE;
        } else {
            // Failed to create user
            return FALSE;
        }
    }
    
    
    ///////////
    
    //  //update food
    // public function updatefood($item, $q){
        
    //     $stmt = $this->conn->prepare( "SELECT quantity from foodlist where foodId=?");
    //     $stmt->bind_param("i", $item);
    //     $stmt = $stmt->execute();
    //     $res = $stmt->get_result();
    
    //     if ($res > $q) {
    //         return true;
    //         // $res=$res-$q;
    //         // $stmt1 = $this->conn->prepare( "UPDATE foodlist set quantity=? where foodId=?");
    //         // $stmt1->bind_param("i", $res,$item);
    //         // $result = $stmt1->execute();
    //         // $stmt1->close();
    //         // $stmt->close();
    //         // if($result){
    //         //     return true;
    //         // }
    //         // else{
    //         //     return false;
    //         // }
            
    //     } else {
    //         // Failed to update
    //         return FALSE;
    //     }
    
    // }
    
    // public function isFoodAlreadyExists($food_name)
    // {
    //     $stmt = $this->conn->prepare("SELECT foodName from foodlist WHERE foodName = ?");
    //     $stmt->bind_param("s", $food_name);
    //     $stmt->execute();
    //     $stmt->store_result();
    //     $num_rows = $stmt->num_rows;
    //     $stmt->close();
    //     return $num_rows > 0;
    // }
    
    //  public function addToFoodlist($food_name,$description,$price,$quantity,$categoryId,$imageLink) {
        
    //         $stmt = $this->conn->prepare("INSERT INTO foodlist(foodName, foodDesc,price, quantity, categoryId,image_url) values(?, ?,?,?,?,?)");
    //         $stmt->bind_param("ssssis", $food_name,$description,$price,$quantity,$categoryId,$imageLink);
    //         $result = $stmt->execute();
    //         $stmt->close();      
    
    //         if ($result) {
    //                 // User successfully inserted
    //                 return TRUE;
    //             } else {
    //                 // Failed to create user
    //                 return FALSE;
    //             }
        
    // }
    
    // public function addItems($food_name,$description,$price,$quantity,$categoryId,$fileSize, $fileTmpName, $fileType, $fileExtension , $filename ,$imageLink)
    // {
    //     if (!$this->isFoodAlreadyExists($food_name))
    //     { 
    //         $currentDir = getcwd();
    //         $errors = [];
    //         $uploadDirectory ="/". $this->getFoodDirectory();
    //         $uploadPath = $currentDir . $uploadDirectory . basename($filename);
    //         $fileExtensions = ['jpeg', 'jpg', 'png', 'JPG', 'PNG', 'JPEG'];
    
    //         if (!in_array($fileExtension, $fileExtensions)) {
    //             return 2;
    //         } 
    //         elseif ($fileSize > 14256000) {
    //             return 3;
    //         } 
    //         else {
    //             if (file_exists($uploadPath)) {
    //                 unlink($uploadPath);
    //             }
    
    //             $isUploaded = move_uploaded_file($fileTmpName, $uploadPath);
    //             if ($isUploaded){
    //                 $this->addToFoodlist($food_name,$description,$price,$quantity,$categoryId,$imageLink);
    //                 return 1;
    //             } 
    //             else {
    //                 return 4;
    //             }
    //         }
    //     }else{
    //         return false;
    //     }
    // }
    
    // public function editItems($food_id,$food_name,$description) {
    //     $stmt = $this->conn->prepare("UPDATE foodlist set foodName=?, foodDesc=? WHERE foodId=?");
    //     $stmt->bind_param("ssi", $food_name,$description,$food_id);
    //     $result = $stmt->execute();
    //     $stmt->close();      

    //     if ($result) {
    //             // User successfully inserted
    //             return TRUE;
    //         } else {
    //             // Failed to create user
    //             return FALSE;
    //         }
    // }
    
    public function delItems($food_id) {
    $stmt = $this->conn->prepare("DELETE from foodlist WHERE foodId=?");
    $stmt->bind_param("i", $food_id);
    $result = $stmt->execute();
    $stmt->close();      

    if ($result) {
            // User successfully inserted
            return TRUE;
        } else {
            // Failed to create user
            return FALSE;
        }
    }
    
    ////////////

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isCustomerExists($email) {
        $stmt = $this->conn->prepare("SELECT customerId from customer WHERE email= ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    
    private function isAdminExists($email) {
        $stmt = $this->conn->prepare("SELECT adminId from admin WHERE email= ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email_id User email id
     */
    public function getCustomerByEmail($email) {
        $stmt = $this->conn->prepare("SELECT customerId, name, email,phoneNum, apikey, created_at, profile_pic FROM customer WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($customerId,$name, $email, $phoneNum, $apikey, $created_at,$profile_pic);
            $stmt->fetch();
            $customer = array();
            $customer["customerId"] =$customerId;
            $customer["name"] = $name;
            $customer["email"] = $email;
            $customer["phoneNum"] =$phoneNum;
            $customer["apikey"] = $apikey;
            $customer["created_at"] = $created_at;
            $customer["profile_pic"] = $profile_pic;
            $stmt->close();
            return $customer;
        } else {
            return NULL;
        }
    }
    
    public function getAdminByEmail($email) {
        $stmt = $this->conn->prepare("SELECT adminId, name, email,post, apikey, created_at FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($adminId,$name, $email, $post, $apikey, $created_at);
            $stmt->fetch();
            $admin = array();
            $admin["adminId"] =$adminId;
            $admin["name"] = $name;
            $admin["email"] = $email;
            $admin["post"] = $post;
            $admin["apikey"] = $apikey;
            $admin["created_at"] = $created_at;
            $stmt->close();
            return $admin;
        } else {
            return NULL;
        }
    }


    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($customerId) {
        $stmt = $this->conn->prepare("SELECT apikey FROM customer WHERE customerId = ?");
        $stmt->bind_param("i", $customerId);
        if ($stmt->execute()) {
            // $api_key = $stmt->get_result()->fetch_assoc();
            // TODO
            $stmt->bind_result($apikey);
            $stmt->close();
            return $apikey;
        } else {
            return NULL;
        }
    }


    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getCustomerId($apikey) {
        $stmt = $this->conn->prepare("SELECT customerId FROM customer WHERE apikey = ?");
        $stmt->bind_param("s", $apikey);
        if ($stmt->execute()) {
            $stmt->bind_result($customerId);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $customerId;
        } else {
            return NULL;
        }
    }



    public function getAdminId($api_key) {
        $stmt = $this->conn->prepare("SELECT adminId FROM admin WHERE apikey = ?");
        $stmt->bind_param("s", $api_key);
        if ($stmt->execute()) {
            $stmt->bind_result($admin_id);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $admin_id;
        } else {
            return NULL;
        }
    }


    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKeyCustomer($apikey) {
        $stmt = $this->conn->prepare("SELECT customerId from customer WHERE apikey = ?");
        $stmt->bind_param("s", $apikey);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }


    public function isValidApiKeyAdmin($api_key) {
        $stmt = $this->conn->prepare("SELECT adminId from admin WHERE apikey = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }
    

}

?>
