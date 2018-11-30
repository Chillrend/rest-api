<?php
 
/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 
// array for JSON response
$response = array();
 
require_once __DIR__ . '/../db-config.php';
 
// connecting to db
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
$con1 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
$con2 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
$con3 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
$con4 = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());

if (isset($_POST['from_zip']) && isset($_POST['to_zip']) && isset($_POST['sender_name']) && isset($_POST['recipient_name']) && isset($_POST['sender_telp']) && isset($_POST['recipient_telp']) && isset($_POST['from_address']) && isset($_POST['to_address']) && isset($_POST['weight']) && isset($_POST['expected_date']) && isset($_POST['awb']) && isset($_POST['shipment_type'])) {
 
    $from_zip= (int)$_POST['from_zip'];
    $to_zip = (int)$_POST['to_zip'];
    $sender_name = $_POST['sender_name'];
    $recipient_name = $_POST['recipient_name'];
    $sender_telp = $_POST['sender_telp'];
    $recipient_telp = $_POST['recipient_telp'];
    $from_address = $_POST['from_address'];
    $to_address = $_POST['to_address'];
    $weight = (int)$_POST['weight'];
    $expected_date = $_POST['expected_date'];
    $awb = $_POST['awb'];
    $shipment_type = (int)$_POST['shipment_type'];
    $zero = 0;
    $sisis = "sisis";
 
 
    // mysql inserting a new row
    $prepareSchedule = "INSERT INTO scheduled_pickup(sender_name, bound_zip, expected_date, done, address) VALUES (?, ?, ?, ?, ?)";
    
    if($stmtSchedule = mysqli_prepare($con, $prepareSchedule)){
        mysqli_stmt_bind_param($stmtSchedule, $sisis, $sender_name, $from_zip, $expected_date, $zero, $from_address);
        $result = mysqli_stmt_execute($stmtSchedule);
    }
    
    $query = "INSERT INTO shipment_log(awb, status, zip_disposal) VALUES ('$awb', 1, '$from_zip')";
    
    if(mysqli_query($con1, $query)){
        $shipmentLogId = mysqli_insert_id($con1);
    }else{
        $shipmentLogId = 0;
    }
    
    $prepareShipment = "INSERT INTO shipment VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if($stmtShipment = mysqli_prepare($con2, $prepareShipment)){
        $paramz = "siissssssiii";
        mysqli_stmt_bind_param($stmtShipment, $paramz, $awb, $from_zip, $to_zip, $sender_name, $recipient_name, $sender_telp, $recipient_telp, $from_address, $to_address, $shipment_type, $weight, $shipmentLogId);
        $resultShipment = mysqli_stmt_execute($stmtShipment);
    }
     
    $query2 = "SELECT city,state,state_abbr,county FROM zip WHERE zip='$from_zip'";
    $query3 = "SELECT city,state,state_abbr,county FROM zip WHERE zip='$to_zip'";
    
    $resultZipFrom = mysqli_query($con3,$query2);
    $resultZipTo = mysqli_query($con4,$query3);
    
    if(!empty($resultZipFrom) && !empty($resultZipTo)){
        if(mysqli_num_rows($resultZipFrom) > 0 && mysqli_num_rows($resultZipTo) > 0){
            $zipSearch = array();
            
            $resultZipFrom = mysqli_fetch_array($resultZipFrom);
            $resultZipTo = mysqli_fetch_array($resultZipTo);
            
            $zipSearch['fromCity'] = $resultZipFrom['city'];
            $zipSearch['fromState'] = $resultZipFrom['state'];
            $zipSearch['fromStateAbbr'] = $resultZipFrom['state_abbr'];
            $zipSearch['fromCounty'] = $resultZipFrom['county'];
            
            $zipSearch['toCity'] = $resultZipTo['city'];
            $zipSearch['toState'] = $resultZipTo['state'];
            $zipSearch['toStateAbbr'] = $resultZipTo['state_abbr'];
            $zipSearch['toCounty'] = $resultZipTo['county'];
            $zipSearch['lastInsertedId'] = $shipmentLogId;
            
        }
    }     
    // check if row inserted or not
    if ($result) {
        // successfully inserted into database
        $response["success"] = 1;
        $response["message"] = "Product successfully created.";
        $response["shipmentData"] = array();
        
        array_push($response["shipmentData"], $zipSearch);
        
        // echoing JSON response
        echo json_encode($response);
    } else {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
 
        // echoing JSON response
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