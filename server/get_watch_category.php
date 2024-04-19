<?php

include('connection.php');

$stmt = $connection->prepare("SELECT * FROM category ");

$stmt->execute();

$watch_category = $stmt->get_result();
$stmt->close();


?>