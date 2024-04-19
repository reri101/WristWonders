<?php

include('connection.php');

$stmt_for_footer = $connection->prepare("SELECT * FROM product LIMIT 5");

$stmt_for_footer->execute();

$ig_products = $stmt_for_footer->get_result();
$stmt_for_footer->close();



?>