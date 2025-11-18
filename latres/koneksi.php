<?php
$server="localhost";
$name="root";
$pass="";
$database="latres";

$conn=mysqli_connect($server,$name,$pass,$database);

if(!isset($conn)){
    die("Connection error!! ".mysqli_connect_error($conn));
}

?>