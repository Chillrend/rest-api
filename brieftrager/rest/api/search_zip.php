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
if (!empty($_GET['zip'])) {
    $zipCode = $_GET['zip'];
 
    // verify is user exists
    $result = mysqli_query($con,"SELECT * FROM zip WHERE zip='$zipCode'");
 
    if (!empty($result)) {
        // check for empty result
        if (mysqli_num_rows($result) > 0) {
 
            $result = mysqli_fetch_array($result);
 
            $zipData = array();
            $zipData["zip"] = $result["zip"];
            $zipData["city"] = $result["city"];
            $zipData["state"] = $result["state"];
            $zipData["state_abbr"] = $result["state_abbr"];
            $zipData["county"] = $result["county"];
            $zipData["latitude"] = $result["latitude"];
            $zipData["longitude"] = $result["longitude"];
            // success
            $response["success"] = 1;
 
            // user node
            $response["zipData"] = array();
 
            array_push($response["zipData"], $zipData);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No ZipCode found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No ZipCode found";
 
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