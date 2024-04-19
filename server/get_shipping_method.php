<?php


$stmt = $connection->prepare("SELECT * FROM shipping ");

$stmt->execute();

$shipping_options = $stmt->get_result();
$stmt->close();


?>