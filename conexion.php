<?php
include 'configuration.php';

$conexion = new mysqli('localhost', 'root', '', 'phplogin');
if (mysqli_connect_errno()) {
    echo "No conectado",mysqli_connect_error();
    exit();
} else {
    // echo "Connected successfully";
}
?>