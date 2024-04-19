<?php


$stmt_user_number = $connection->prepare("SELECT COUNT(client_ID) as user_number
                                        FROM `user`");

$stmt_user_number->execute();

$total_user_number = $stmt_user_number->get_result();
$stmt_user_number->close();


?>