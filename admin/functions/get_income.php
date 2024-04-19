<?php

// łącznie
$stmt_total_cost = $connection->prepare("SELECT SUM(`order`.final_cost) as total_cost
                                        FROM `order`");

$stmt_total_cost->execute();

$total_cost_result = $stmt_total_cost->get_result();
$stmt_total_cost->close();

// Zarobki z dzisiaj
$current_date = date("Y-m-d");
$stmt_todays_cost = $connection->prepare("SELECT SUM(`order`.final_cost) as todays_cost
                                        FROM `order`
                                        WHERE DATE(`order`.order_date) = ? ");
$stmt_todays_cost->bind_param("s", $current_date);
$stmt_todays_cost->execute();

$todays_cost_result = $stmt_todays_cost->get_result();
$stmt_todays_cost->close();

// Zarobki z wczoraj
$yesterday_date = date("Y-m-d", strtotime("-1 days"));
$stmt_yesterdays_cost = $connection->prepare("SELECT SUM(`order`.final_cost) as yesterdays_cost
                                            FROM `order`
                                            WHERE DATE(`order`.order_date) = ? ");
$stmt_yesterdays_cost->bind_param("s", $yesterday_date);
$stmt_yesterdays_cost->execute();

$yesterdays_cost_result = $stmt_yesterdays_cost->get_result();
$stmt_yesterdays_cost->close();

$yesterdays_cost = $yesterdays_cost_result->fetch_assoc()['yesterdays_cost'];
$todays_cost = $todays_cost_result->fetch_assoc()['todays_cost'];

if($yesterdays_cost == 0) 
    $today_compare_to_y = 0;
else 
    $today_compare_to_y = round(($todays_cost / $yesterdays_cost) * 100, 2);
?>