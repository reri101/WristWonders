<?php


$connection = mysqli_connect("localhost","root","","watchshop_v2") or die("Problem z połączeniem do bazy danych");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>