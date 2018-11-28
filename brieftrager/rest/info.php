<?php
require_once __DIR__ . '/db-config.php';

$username = "akar";
$password = "pohonberingin"
    
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());

$result = mysqli_query($con,"SELECT * FROM user WHERE username = $username AND password = $password");

if (!empty($result)) {

?>