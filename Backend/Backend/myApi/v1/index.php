<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$customerId = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $apikey = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKeyCustomer($apikey)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $customerId;
            // get user primary key id
            $customerId = $db->getCustomerId($apikey);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}



function authenticateAdmin(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKeyAdmin($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getAdminId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}
/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 
 * method - POST
 * params - name, email, password
 */
$app->post('/registerCustomer', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('name', 'email', 'password', 'phoneNum'));

            $response = array();

            // reading post params
            $name = $app->request->post('name');
            $email = $app->request->post('email');
            $password = $app->request->post('password');

            $phoneNum = $app->request->post('phoneNum');

           
            // validating email address
            validateEmail($email);

            $db = new DbHandler();
            $res = $db->createCustomer($name, $email, $password, $phoneNum);

            if ($res == USER_CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully registered";
            } else if ($res == USER_CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing";
            } else if ($res == USER_ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this email already existed";
            }
            // echo json response
            echoRespnse(201, $response);
        });
        
   
$app->post('/register_admin', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('name','email', 'password'));

        $response = array();

        // reading post params
        $name = $app->request->post('name');
        $email = $app->request->post('email');
        $password = $app->request->post('password');
       

        $db = new DbHandler();
        $res = $db->createAdmin($name,$email, $password);

        if ($res == USER_CREATED_SUCCESSFULLY) {
            $response["error"] = false;
            $response["message"] = "You are successfully registered";
        } else if ($res == USER_CREATE_FAILED) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while registereing";
        } else if ($res == USER_ALREADY_EXISTED) {
            $response["error"] = true;
            $response["message"] = "Sorry, this user already existed";
        }
        // echo json response
        echoRespnse(201, $response);
    });


/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/loginCustomer', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkCustomerLogin($email, $password)) {
                // get the user by email
                $customer = $db->getCustomerByEmail($email);

                if ($customer != NULL) {
                    $response["error"] = false;
                    $response['customerId']= $customer['customerId'];
                    $response['name'] = $customer['name'];
                    $response['email'] = $customer['email'];
                    $response['phoneNum']=$customer['phoneNum'];
                    $response['apikey'] = $customer['apikey'];
                    $response['created_at'] = $customer['created_at'];
                    $response['profile_pic'] =$customer['profile_pic'];
                } else {
                    // unknown error occurred
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                }
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoRespnse(200, $response);
        });
        
        
$app->post('/loginAdmin', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkAdminLogin($email, $password)) {
                // get the user by email
                $admin = $db->getAdminByEmail($email);

                if ($admin != NULL) {
                    $response["error"] = false;
                    $response['adminId']= $admin['adminId'];
                    $response['name'] = $admin['name'];
                    $response['email'] = $admin['email'];
                    $response['post'] = $admin['post'];
                    $response['apikey'] = $admin['apikey'];
                    $response['created_at'] = $admin['created_at'];
                  
                } else {
                    // unknown error occurred
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                }
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoRespnse(200, $response);
        }); 
        
        

///edit profile
$app->post('/edit_profile', 'authenticate', function() use ($app) {
    global $customerId;
        
    $uploadDirectoryLink = 'https://www.riyanakarmi.com.np/profilePicture/';
    $response=array();
    $has_pp = false;

    if(isset($_FILES['file'])){
        $has_pp = true;
    }
    if($has_pp || isset($_POST['email']) || isset($_POST['name']) || isset($_POST['password']) || isset($_POST['phoneNum']) || isset($_POST['oldProfileLink'])){
        $fileName ;
        $fileSize =null;
        $fileTmpName = null;
        $fileType = null;
        $fileExtension = null ;
        $filename = null ;
        $imageLink = null;
        $finalEmailId = null;
        
        $db = new DbHandler();
        if($has_pp){
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileType = $_FILES['file']['type'];
        
            $tmpo = explode('.', $fileName);
            $fileExtension = end($tmpo);
            $path_parts = pathinfo($_FILES["file"]["name"]);
            $filename = $path_parts['filename'] . '_' . time() . '_'.$customerId. '.' . $path_parts['extension']  ;
        
            $year = date("Y");
            $month = date("m");
            $short_dir = $year . "/" . $month;
            $imageLink =$uploadDirectoryLink . $short_dir . "/" . $filename;
        
        }
     
        $full_name = $app->request->post('name');
        $password = $app->request->post('password');
        $phone_number = $app->request->post('phoneNum');
        $email_id = $app->request->post('email');
        $oldProfileLink = $app->request->post('oldProfileLink');
        
        
        // validating email address
        validateEmail($email_id);
        $finalEmailId = $email_id;
        
        
        if(is_null($imageLink)){
            $finalImageLink=$oldProfileLink;
        }else{
            $finalImageLink=$imageLink;
        }

        
        if($db->editUser($customerId, $full_name, $password, $finalEmailId, $phone_number, $fileSize, $fileTmpName, $fileType, $fileExtension , $filename ,$finalImageLink, $has_pp))
        {
           $customer=$db->getCustomerById($customerId);
                if ($customer != NULL) {
                    $response["error"] = false;
                    $response["message"] = "User info changed successfully";
                    $response['name'] = $customer['name'];
                    $response['email'] = $customer['email'];
                    $response["profile_pic"] = $customer['profile_pic'];
                    $response["phoneNum"] = $customer['phoneNum'];
                    echoRespnse(200, $response);
                }else{
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                    echoRespnse(200, $response);    
                }
            
        }else
        {
            $response["error"] = true;
            $response["message"] = "User info not changed";
            echoRespnse(300, $response);
          

        } 
        
        }
        else{
            $response["message"] = "Nothing to be changed";
            echoRespnse(300, $response);
        }

}); 


///edit admin profile
$app->post('/edit_admin_profile', 'authenticateAdmin', function() use ($app) {
  global $user_id;
        verifyRequiredParams(array('name', 'password','email'));
        $db = new DbHandler();
     
        $full_name = $app->request->post('name');
        $password = $app->request->post('password');
        $email_id = $app->request->post('email');
        
        
        // validating email address
        validateEmail($email_id);
        $finalEmailId = $email_id;
        
        
        if($db->editAdminUser($user_id, $full_name, $password, $finalEmailId))
        {
           $admin=$db->getAdminById($user_id);
                if ($admin != NULL) {
                    $response["error"] = false;
                    $response["message"] = "User info changed successfully";
                    $response['name'] = $admin['name'];
                    $response['email'] = $admin['email'];
                    echoRespnse(200, $response);
                }else{
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                    echoRespnse(200, $response);    
                }
            
        }else
        {
            $response["error"] = true;
            $response["message"] = "User info not changed";
            echoRespnse(300, $response);
          

        } 
        
});  

$app->post('/orders','authenticate', function() use ($app) {
    
    global $customerId;
    verifyRequiredParams(array('pay_method','pay_status'));

    $response = array();
    $pay_method = $app->request->post('pay_method');
    $pay_status= $app->request ->post('pay_status');
       
    $db = new DbHandler();
    $res = $db->orderBy($customerId,$pay_method,$pay_status);
    if ($res ==1) {
        $response["error"] = false;
        $response["message"] = "successfully ordered";
    }
    else if($res == 10)  {
        $response["error"] = true;
        $response["message"] = "No Foods in cart";
    } 
    else  {
        $response["error"] = true;
        $response["message"] = "Order unsuccessful";
    } 
    
    
    echoRespnse(200, $response);

});


//addquantity to cart
$app->post('/update_to_quantity','authenticate', function() use ($app) {
    global $customerId;

    verifyRequiredParams(array('customerId', 'foodId', 'quantity','price'));

    $response = array();

    $customerId= $app ->request->post('customerId');
    $foodId = $app->request->post('foodId');
    $q = $app->request->post('quantity');
    $price = $app->request->post('price');
   
    $db = new DbHandler();
    $res = $db->updateQuantity($customerId, $foodId, $q, $price);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully updated";
    } else  {
        $response["error"] = true;
        $response["message"] = "Updating unsuccessful";
    } 
    echoRespnse(200, $response);
    });

////////////////

////view order history 
 $app->post('/view_order_history','authenticate', function() use ($app) {
    // check for required params
    global $customerId;

    $response = array();

    // reading post params

    $db = new DbHandler();
    $res = $db->showOrders($customerId);
    if ($res != NULL) {
        $response["error"] = false;
        $response['data'] = $res;
        $response['message'] ="successfully done";
    }
    else if($res == 0){
        // unknown error occurred
        $response['error'] = false;
        $response['message'] = "No data found";
        $response['data'] = NULL; 
    }
    else {
        // unknown error occurred
        $response['error'] = true;
        $response['message'] = "Error!";
        $response['data'] = NULL; 
    }
    echoRespnse(200, $response);
    });
    
//view each order from orders
    
 $app->post('/view_each_order','authenticate', function() use ($app) {
    // check for required params
    global $customerId;
    verifyRequiredParams(array('order_id'));

    $response = array();
    $order_id = $app->request->post('order_id');

    // reading post params

    $db = new DbHandler();
    $res = $db->showeach($order_id);
    
    if ($res != NULL) {
        $response["error"] = false;
        $response['data'] = $res;
        $response['message'] ="successfully done";
    }
    else if($res == 0){
        // unknown error occurred
        $response['error'] = false;
        $response['message'] = "No data found";
        $response['data'] = NULL; 
    }
    else {
        // unknown error occurred
        $response['error'] = true;
        $response['message'] = "Error!";
        $response['data'] = NULL; 
    }
    echoRespnse(200, $response);
    });  
  
    
$app->post('/view_cart','authenticate', function() use ($app) {
    // check for required params
    global $customerId;

    $response = array();

    // reading post params

    $db = new DbHandler();
    $out = $db->getCart($customerId);
    $res = $db->getCartProducts($out);
    
    if ($res != NULL) {
        $response["error"] = false;
        $response['data'] = $res;
        $response['message'] ="successfully Fetched";
    }
    else if($res == 0){
        // unknown error occurred
        $response['error'] = false;
        $response['message'] = "No data found";
        $response['data'] = NULL; 
    }
    else {
        // unknown error occurred
        $response['error'] = true;
        $response['message'] = "Error!";
        $response['data'] = NULL; 
    }
    echoRespnse(200, $response);
    });


    
//view customer by admin
$app->post('/view_customer','authenticateAdmin', function() use ($app) {
            // check for required params
            //global $customerId;

            $response = array();

            // reading post params

            $db = new DbHandler();
            $res = $db->showCustomers();
            if ($res != NULL) {
                $response["error"] = false;
                $response['data'] = $res;
                $response['message'] ="successfully done";
            }
            else if($res == 0){
                // unknown error occurred
                $response['error'] = false;
                $response['message'] = "No data found";
                $response['data'] = NULL; 
            }
            else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Error!";
                $response['data'] = NULL; 
            }
            echoRespnse(200, $response);
            });

//view category
$app->post('/view_categories','authenticate', function() use ($app) {
            // check for required params
            global $customerId;

            $response = array();

            // reading post params

            $db = new DbHandler();
            $res = $db->showCategories();
            if ($res != NULL) {
                $response["error"] = false;
                $response['data'] = $res;
                $response['message'] ="successfully done";
            }
            else if($res == 0){
                // unknown error occurred
                $response['error'] = false;
                $response['message'] = "No data found";
                $response['data'] = NULL; 
            }
            else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Error!";
                $response['data'] = NULL; 
            }
            echoRespnse(200, $response);
            });


//view category by admin
$app->post('/view_categoriesAdmin','authenticateAdmin', function() use ($app) {
            // check for required params
            //global $customerId;

            $response = array();

            // reading post params

            $db = new DbHandler();
            $res = $db->showCategories();
            if ($res != NULL) {
                $response["error"] = false;
                $response['data'] = $res;
                $response['message'] ="successfully done";
            }
            else if($res == 0){
                // unknown error occurred
                $response['error'] = false;
                $response['message'] = "No data found";
                $response['data'] = NULL; 
            }
            else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Error!";
                $response['data'] = NULL; 
            }
            echoRespnse(200, $response);
            });

//////////////

//view food items
$app->post('/view_items','authenticate', function() use ($app) {
            // check for required params
            global $customerId;

            $response = array();

            // reading post params

            $db = new DbHandler();
            $res = $db->showItems();
        //    $res= $db ->showItemWithCat($out);
            if ($res != NULL) {
                $response["error"] = false;
                $response['data'] = $res;
                $response['message'] ="successfully done";
            }
            else if($res == 0){
                // unknown error occurred
                $response['error'] = false;
                $response['message'] = "No data found";
                $response['data'] = NULL; 
            }
            else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Error!";
                $response['data'] = NULL; 
            }
            echoRespnse(200, $response);
            });
            
//view food items by admin
$app->post('/view_foodItemsAdmin','authenticateAdmin', function() use ($app) {
            // check for required params
           // global $customerId;

            $response = array();

            // reading post params

            $db = new DbHandler();
            $res = $db->showItems();
        //    $res= $db ->showItemWithCat($out);
            if ($res != NULL) {
                $response["error"] = false;
                $response['data'] = $res;
                $response['message'] ="successfully done";
            }
            else if($res == 0){
                // unknown error occurred
                $response['error'] = false;
                $response['message'] = "No data found";
                $response['data'] = NULL; 
            }
            else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "Error!";
                $response['data'] = NULL; 
            }
            echoRespnse(200, $response);
            });

//add to cart
$app->post('/add_cart','authenticate', function() use ($app) {
    global $customerId;

    verifyRequiredParams(array('foodId', 'quantity','price'));

    $response = array();

    $foodId = $app->request->post('foodId');
    $q = $app->request->post('quantity');
    $price = $app->request->post('price');
  
   
    $db = new DbHandler();
    $res = $db->addtocart($customerId,$foodId, $q, $price);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully added";
    } else  {
        $response["error"] = true;
        $response["message"] = "Adding unsuccessful";
    } 
    echoRespnse(200, $response);
    });
    
//delete cart
$app->post('/delete_cart','authenticate', function() use ($app) {
    global $customerId;

    verifyRequiredParams(array('customerId', 'foodId'));

    $response = array();

    $customerId = $app->request->post('customerId');
    $foodId = $app->request->post('foodId');       

         
    $db = new DbHandler();
    $res = $db->deleteFromCart($customerId, $foodId);
    
    if($res){
        $response["error"] = $res;
        $response["message"] = "successfully deleted";
    
    }else {
            
        $response["error"] = $res;
        $response["message"] = "Deleting unsuccessful";
    } 
    echoRespnse(200, $response);
    });
    



//-----without authentication-------


$app->post('/add_category','authenticateAdmin', function() use ($app) {
    global $user_id;

    verifyRequiredParams(array('categoryName', 'categoryDesc'));

            $response = array();

            // reading post params
            $category_name = $app->request->post('categoryName');
            $description = $app->request->post('categoryDesc');
           
   
    $db = new DbHandler();
    $res = $db->addCategory($category_name,$description);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully added";
    } else  {
        $response["error"] = true;
        $response["message"] = "Adding unsuccessful";
    } 
    echoRespnse(200, $response);
    });
    
//editing category

$app->post('/edit_category','authenticateAdmin', function() use ($app) {
   // global $user_id;

    verifyRequiredParams(array('categoryId','categoryName', 'categoryDesc'));

            $response = array();

            // reading post params
            $category_id = $app->request->post('categoryId');
            $category_name = $app->request->post('categoryName');
            $description = $app->request->post('categoryDesc');
           
   
    $db = new DbHandler();
    $res = $db->editCategory($category_id,$category_name,$description);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully edited";
    } else  {
        $response["error"] = true;
        $response["message"] = "Editing unsuccessful";
    } 
    echoRespnse(200, $response);
    });


/** deleting category
*/

$app->post('/delete_category','authenticateAdmin', function() use ($app) {
    global $user_id;

    verifyRequiredParams(array('categoryId'));

    $response = array();

    // reading post params
    $category_id= $app->request->post('categoryId');
           
   
    $db = new DbHandler();
    $res = $db->delCategory($category_id);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully deleted";
    } else  {
        $response["error"] = true;
        $response["message"] = "Deleting unsuccessful";
    } 
    echoRespnse(200, $response);
    });



// $app->post('/add_foodItems','authenticateAdmin', function() use ($app) {
//     global $user_id;

//     verifyRequiredParams(array('foodName', 'foodDesc','price','quantity','categoryId'));
//     $uploadDirectoryLink = 'https://www.riyanakarmi.com.np/Backend/adminSection/FoodImage/';
    
//     $response = array();
    
//      if(isset($_FILES['file'])){
//         $fileName = $_FILES['file']['name'];
//         $fileSize = $_FILES['file']['size'];
//         $fileTmpName = $_FILES['file']['tmp_name'];
//         $fileType = $_FILES['file']['type'];

//         $tmpo = explode('.', $fileName);
//         $fileExtension = end($tmpo);
//         $path_parts = pathinfo($_FILES["file"]["name"]);
//         $filename = $path_parts['filename'] . '_' . time() . '.' . $path_parts['extension']  ;

//         $year = date("Y");
//         $month = date("m");
//         $short_dir = $year . "/" . $month;
//         $imageLink =$uploadDirectoryLink . $short_dir . "/" . $filename;

//          // reading post params
//         $food_name = $app->request->post('foodName');
//         $description = $app->request->post('foodDesc');
//         $price = $app -> request -> post('price');
//         $quantity= $app -> request -> post ('quantity');
//         $categoryId= $app -> request -> post ('categoryId');
//         //$image_url= $imageLink;
         
//         $db = new DbHandler();
//         $res = $db->addItems($food_name,$description,$price,$quantity,$categoryId,$fileSize, $fileTmpName, $fileType, $fileExtension , $filename ,$imageLink);
    
//         if ($res) {
//             $response["error"] = false;
//             $response["message"] = "successfully added";
//         } else  {
//             $response["error"] = true;
//             $response["message"] = "Adding unsuccessful";
//         } 
//         echoRespnse(200, $response);
//     }
//     else{
//         $response["error"] = true;
//         $response["message"] = "Please provide food image!!";
//         echoRespnse(400, $response);
//     } 
        
    
// });
    
// //editing foodItems

// $app->post('/edit_foodItems','authenticateAdmin', function() use ($app) {
//   // global $user_id;

//     verifyRequiredParams(array('foodId','foodName', 'foodDesc','price','quantity','categoryId','image_url'));

//             $response = array();

//             // reading post params
//             $food_id = $app->request->post('foodId');
//             $food_name = $app->request->post('foodName');
//             $description = $app->request->post('foodDesc');
           
   
//     $db = new DbHandler();
//     $res = $db->editItems($food_id,$food_name,$description);

//     if ($res) {
//         $response["error"] = false;
//         $response["message"] = "successfully edited";
//     } else  {
//         $response["error"] = true;
//         $response["message"] = "Editing unsuccessful";
//     } 
//     echoRespnse(200, $response);
//     });


/** deleting food items
*/

$app->post('/delete_foodItems','authenticateAdmin', function() use ($app) {
   // global $user_id;

    verifyRequiredParams(array('foodId'));

    $response = array();

    // reading post params
    $food_id= $app->request->post('foodId');
           
   
    $db = new DbHandler();
    $res = $db->delItems($food_id);

    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully deleted";
    } else  {
        $response["error"] = true;
        $response["message"] = "Deleting unsuccessful";
    } 
    echoRespnse(200, $response);
    });



////view order history 
 $app->post('/view_order_admin','authenticateAdmin', function() use ($app) {
    // check for required params
   // global $customerId;

    $response = array();

    // reading post params

    $db = new DbHandler();
    $res = $db->getOrders();
    if ($res != NULL) {
        $response["error"] = false;
        $response['data'] = $res;
        $response['message'] ="successfully done";
    }
    else if($res == 0){
        // unknown error occurred
        $response['error'] = false;
        $response['message'] = "No data found";
        $response['data'] = NULL; 
    }
    else {
        // unknown error occurred
        $response['error'] = true;
        $response['message'] = "Error!";
        $response['data'] = NULL; 
    }
    echoRespnse(200, $response);
    });
    
//view each order from orders
    
 $app->post('/each_order','authenticateAdmin', function() use ($app) {
    // check for required params
   // global $customerId;
    verifyRequiredParams(array('order_id'));

    $response = array();
    $order_id = $app->request->post('order_id');

    // reading post params

    $db = new DbHandler();
    $res = $db->showeach($order_id);
    
    if ($res != NULL) {
        $response["error"] = false;
        $response['data'] = $res;
        $response['message'] ="successfully done";
    }
    else if($res == 0){
        // unknown error occurred
        $response['error'] = false;
        $response['message'] = "No data found";
        $response['data'] = NULL; 
    }
    else {
        // unknown error occurred
        $response['error'] = true;
        $response['message'] = "Error!";
        $response['data'] = NULL; 
    }
    echoRespnse(200, $response);
    });  
    
//////
$app->post('/change_orderstatus','authenticateAdmin', function() use ($app) {
    // check for required params
   
    verifyRequiredParams(array('order_id','status'));

    $response = array();
    $order_id = $app->request->post('order_id');
    $status = $app->request->post('status');

    // reading post params

    $db = new DbHandler();
    $res = $db->updateOrderStatus($order_id,$status);
    
    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully updated";
    } else  {
        $response["error"] = true;
        $response["message"] = "Updating unsuccessful";
    }
    echoRespnse(200, $response);
    });  
  
$app->post('/change_paystatus','authenticateAdmin', function() use ($app) {
    // check for required params
   // global $customerId;
    verifyRequiredParams(array('order_id','pay_status'));

    $response = array();
    $order_id = $app->request->post('order_id');
    $paystatus = $app->request->post('pay_status');

    // reading post params

    $db = new DbHandler();
    $res = $db->updatePayStatus($order_id,$paystatus);
    
    if ($res) {
        $response["error"] = false;
        $response["message"] = "successfully updated";
    } else  {
        $response["error"] = true;
        $response["message"] = "Updating unsuccessful";
    }
    echoRespnse(200, $response);
    });  
  

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>