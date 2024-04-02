<?php 

function getConnection(){

    $conn = new mysqli("localhost", "root", "", "auth");
    if ($conn->connect_error)
        return false;
    return $conn;
}


?>