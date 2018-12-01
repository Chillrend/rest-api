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
    $con1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
    $con2 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
    $con3 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
    $con4 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
    $con5 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
    $con6 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
 
// check for post url param
//!empty($_POST['username']) && !empty($_POST['password'])
if (!empty($_GET['awb'])) {
    
    $awb = $_GET['awb'];
 
    $resultShipment = mysqli_query($con, "SELECT * FROM shipment WHERE awb = '$awb'");
 
    if (!empty($resultShipment)) {
        // check for empty result
        if (mysqli_num_rows($resultShipment) > 0) {
 
            $resultShipment = mysqli_fetch_array($resultShipment);
 
            $trackingData = array();
            $trackingData["awb"] = $resultShipment["awb"];
            $trackingData["from_zip"] = $resultShipment["from_zip"];
            $trackingData["to_zip"] = $resultShipment["to_zip"];
            $trackingData["sender_name"] = $resultShipment["sender_name"];
            $trackingData["recipient_name"] = $resultShipment["recipient_name"];
            $trackingData["sender_telp"] = $resultShipment["sender_telp"];
            $trackingData["recipient_telp"] = $resultShipment["recipient_telp"];
            $trackingData["from_address"] = $resultShipment["from_address"];
            $trackingData["to_address"] = $resultShipment["to_address"];
            $trackingData["weight"] = $resultShipment["weight"];
            
            
            $from_zip = $resultShipment["from_zip"];
            $to_zip = $resultShipment["to_zip"];
            $shipment_type = $resultShipment["shipment_type"];
            $last_status = $resultShipment["last_status"];
            
            $resultFromZip = mysqli_query($con1,"SELECT * FROM zip WHERE zip = '$from_zip'");
            
            if(!empty($resultFromZip)){
                if(mysqli_num_rows($resultFromZip) > 0){
                    $resultFromZip = mysqli_fetch_array($resultFromZip);
                    
                    $trackingData["from_city"] = $resultFromZip["city"];
                    $trackingData["from_state"] = $resultFromZip["state"];
                    $trackingData["from_state_abbr"] = $resultFromZip["state_abbr"];
                }else{
                    $response["success"] = 0;
                    $response["message"] = "No Zip Found at From Zip!";
                    echo json_encode($response);
                    exit();
                }
            }else{
                $response["success"] = 0;
                $response["message"] = "No Zip Found at From Zip!";
                echo json_encode($response);
                exit();
            }
            
            $resultToZip = mysqli_query($con2,"SELECT * FROM zip WHERE zip = '$to_zip'");
            
            if(!empty($resultToZip)){
                if(mysqli_num_rows($resultToZip) > 0){
                    $resultToZip = mysqli_fetch_array($resultToZip);
                    
                    $trackingData["to_city"] = $resultToZip["city"];
                    $trackingData["to_state"] = $resultToZip["state"];
                    $trackingData["to_state_abbr"] = $resultToZip["state_abbr"];
                }else{
                    $response["success"] = 0;
                    $response["message"] = "No Zip Found at To Zip!";
                    echo json_encode($response);
                    exit();
                }
            }else{
                $response["success"] = 0;
                $response["message"] = "No Zip Found at To Zip!";
                echo json_encode($response);
                exit();
            }
            
            $resultLastStatus = mysqli_query($con3,"SELECT status,zip_disposal FROM shipment_log WHERE id = $last_status");
            
            if(!empty($resultLastStatus)){
                if(mysqli_num_rows($resultLastStatus) > 0){
                    $resultLastStatus = mysqli_fetch_array($resultLastStatus);
                    
                    $shipment_status_id = $resultLastStatus["status"];
                    $now_at_zip = $resultLastStatus["zip_disposal"];
                    
                    $resultNowAtZip = mysqli_query($con4,"SELECT * FROM zip WHERE zip = '$now_at_zip'");
            
                    if(!empty($resultNowAtZip)){
                        if(mysqli_num_rows($resultNowAtZip) > 0){
                            $resultNowAtZip = mysqli_fetch_array($resultNowAtZip);

                            $trackingData["now_at_zip"] = $resultNowAtZip["zip"];
                            $trackingData["now_at_city"] = $resultNowAtZip["city"];
                            $trackingData["now_at_state"] = $resultNowAtZip["state"];
                            $trackingData["now_at_state_abbr"] = $resultNowAtZip["state_abbr"];
                        }else{
                            $response["success"] = 0;
                            $response["message"] = "No Zip Found at Now At Zip";
                            echo json_encode($response);
                            exit();
                        }
                    }else{
                        $response["success"] = 0;
                        $response["message"] = "No Zip Found at Now At Zip";
                        echo json_encode($response);
                        exit();
                    }
                    
                    $resultStatusName = mysqli_query($con5,"SELECT status FROM shipment_status WHERE id = '$shipment_status_id'");
            
                    if(!empty($resultStatusName)){
                        if(mysqli_num_rows($resultStatusName) > 0){
                            $resultStatusName = mysqli_fetch_array($resultStatusName);

                            $trackingData["status"] = $resultStatusName["status"];
                        }else{
                            $response["success"] = 0;
                            $response["message"] = "No Status Name Found!";
                            echo json_encode($response);
                            exit();
                        }
                    }else{
                        $response["success"] = 0;
                        $response["message"] = "No Status Name Found!";
                        echo json_encode($response);
                        exit();
                    }
                    
                }else{
                    $response["success"] = 0;
                    $response["message"] = "Last Status Not Found!";
                    echo json_encode($response);
                    exit();
                }
            }else{
                $response["success"] = 0;
                $response["message"] = "Last Status not Found!";
                echo json_encode($response);
                exit();
            }
            
            // success
            $response["success"] = 1;
 
            // user node
            $response["trackingData"] = array();
 
            array_push($response["trackingData"], $trackingData);
 
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
        $response["message"] = "No Shipment found!";
 
        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "GET DATA IS MISSING!";
 
    // echoing JSON response
    echo json_encode($response);
}
?>