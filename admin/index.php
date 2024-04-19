<?php

session_start();
include('../server/connection.php');

if(!isset($_SESSION['admin']['logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['logout'])){
    if(isset($_SESSION['admin']['logged_in'])){
        unset($_SESSION['admin']);
        header('location: login.php');
        exit;
    }
}

include('functions/get_all_users.php'); 
include('functions/get_all_orders.php'); 
include('functions/get_income.php'); 
include('functions/get_user_number.php'); 
include('functions/get_popular_category.php'); 


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="assets/scss/style.css"/>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <main>
        <?php include('includes/navbar.php'); ?>


        <div class="main_content">
            <div class="row">
                <div class="cards_row">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon_container">
                                <img src="../assets/imgs/icons/money.png" alt="Dodaj zegarek" class="icon">
                            </div>
                            <div class="text-end">
                                <p">Łączny przychód</p>
                                <?php $row = $total_cost_result->fetch_assoc(); ?>
                                <h4><?php echo $row['total_cost'];?> zł</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer">
                            <p><span>+55% </span>zysku</p>
                        </div>
                    </div>
                </div>
                <div class="cards_row">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon_container">
                                <img src="../assets/imgs/icons/group.png" alt="Dodaj zegarek" class="icon">
                            </div>
                            <div class="text-end">
                                <p>Liczba użytkowników</p>
                                <?php $row = $total_user_number->fetch_assoc(); ?>
                                <h4><?php echo $row['user_number'];?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer">
                            <p class=""><span class="">100% </span>zadowolonych klientów</p>
                        </div>
                    </div>
                </div>
                <div class="cards_row">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon_container">
                                <img src="../assets/imgs/icons/category.png" alt="Dodaj zegarek" class="icon">
                            </div>
                            <div class="text-end">
                                <p>Najpopularniejsza kategoria</p>
                                <h4><?php echo $t_m_popular_cat['Category'];?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer">
                            <p class="">Ilość sprzedanych z niej zegarków: <span class=""><?php echo $t_m_popular_cat['totalValue'];?></span></p>
                        </div>
                    </div>
                </div>
                <div class="cards_row">
                    <div class="card">
                        <div class="card-header">
                            <div class="icon_container">
                                <img src="../assets/imgs/icons/money_2.png" alt="Dodaj zegarek" class="icon">
                            </div>
                            <div class="text-end">
                                <p>Dzisiejsza sprzedaż</p>
                                <h4><?php if($todays_cost == 0) echo 0; else echo $todays_cost;?> zł</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer">
                            <p class=""><span class=""><?php if($today_compare_to_y >= 0) echo "+".$today_compare_to_y; else echo "-".$today_compare_to_y;?>% </span>w porównaniu do wczoraj</p>
                        </div>
                    </div>
                </div>
        </div>

        
        <div class="main_container">
            <div class="cont_1 container">
                <div class="card">
                    <div class="card-header formses-header">
                        <h3>Użytkownicy</h3>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>rola</th>
                                <th>Ilość zamówień</th>
                            </tr>
        
                            <?php while($row = $all_users->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $row['client_ID'];?></td>
                                <td><?php echo $row['first_name']; ?></td>
                                <td><?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td><?php echo $row['order_count']; ?></td>
                            </tr>
                            <?php }?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="cont_2 container">
                <div class="card">
                    <div class="card-header formses-header">
                        <h3>Ostatnie zamówienia</h3>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Łączna cena</th>
                                <th>Kupujący</th>
                                <th>Status</th>
                                <th>Data złożenia</th>
                            </tr>
        
                            <?php while($row = $all_orders->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $row['order_ID'];?></td>
                                <td><?php echo $row['final_cost']; ?></td>
                                <td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
                                <td><?php echo $row['status_type']; ?></td>
                                <td><?php echo $row['order_date']; ?></td>
                            </tr>
                            <?php }?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>





    <script src="assets/js/main.js"></script>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var status = urlParams.get('login_succes');

        if (status === 'Logowanie powiodło się') {
            showPopup('Logowanie powiodło się ✔', true);
        }
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
