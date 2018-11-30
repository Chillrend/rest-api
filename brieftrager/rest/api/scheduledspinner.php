
<?php
    
// include db connect class
require_once __DIR__ . '/../db-config.php';
 
// connecting to db
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());

    $response = array();
    $response["method"] = array();
     
    // Mysql select query
    $result = mysqli_query($con, "SELECT * FROM shipment_type");
     
    while($row = mysqli_fetch_array($result)){
        // temporary array to create single category
        $res = array();
        $res["id"] = $row["id"];
        $res["name"] = $row["name"];
        $res["abbr"] = $row["abbr"];
         
        // push category to final json array
        array_push($response["method"], $res);
    }
     
    // keeping response header to json
    header('Content-Type: application/json');
     
    // echoing json result
    echo json_encode($response);

?>