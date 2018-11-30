<?php
 
/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
// include db connect class
    require_once __DIR__ . '/../db-config.php';

    // connecting to db
    $con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
 
// check for post url param
//!empty($_POST['username']) && !empty($_POST['password'])
if (!empty($_GET['username']) && !empty($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];
 
    // verify is user exists
    $result = mysqli_query($con,"SELECT * FROM user WHERE username = '$username' AND password = '$password'");
 
    if (!empty($result)) {
        // check for empty result
        if (mysqli_num_rows($result) > 0) {
 
            $result = mysqli_fetch_array($result);
 
            $user = array();
            $user["username"] = $result["username"];
            $user["password"] = $result["password"];
            $user["email"] = $result["email"];
            $user["phone"] = $result["phone"];
            $user["name"] = $result["name"];
            $user["address"] = $result["address"];
            $user["zip"] = $result["zip"];
            $user["gender"] = $result["gender"];
            $user["pp_image"] = $result["pp_image"];
            // success
            $response["success"] = 1;
 
            // user node
            $response["userdata"] = array();
 
            array_push($response["userdata"], $user);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No User found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No User found";
 
        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>