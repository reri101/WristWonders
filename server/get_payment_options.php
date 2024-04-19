<?php


$stmt = $connection->prepare("SELECT * FROM payment ");

$stmt->execute();

$payment_options = $stmt->get_result();
$stmt->close();


?>