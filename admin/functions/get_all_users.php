<?php


$stmt_all_users = $connection->prepare("SELECT user.*, COUNT(order.order_ID) AS order_count
                                       FROM user
                                       LEFT JOIN `order` ON user.client_ID = `order`.client_ID
                                       INNER JOIN status
                                       GROUP BY user.client_ID
                                       ORDER BY user.client_ID DESC");

$stmt_all_users->execute();

$all_users = $stmt_all_users->get_result();
$stmt_all_users->close();

?>