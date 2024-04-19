<?php

session_start();
include('server/connection.php');

if(!isset($_SESSION['user']['logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['logout'])){
    if(isset($_SESSION['user']['logged_in'])){
        unset($_SESSION['user']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['change_password'])){
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_SESSION['user']['email'];

    if ($password != $confirmPassword){
        header('location: account.php?error=Hasla nie sa identyczne');
    }else if (strlen($password) < 6){
        header('location: account.php?error=Haslo musi skladac sie z przynajmniej 6 znakow');
    }else{
        $stmt = $connection->prepare("UPDATE user SET `password` = ? WHERE `login` = ?");
        $stmt->bind_param('ss',md5($password),$email);
        if($stmt->execute()){
            header('location: account.php?message=Hasło zostało zmienione pomyślnie');
            $stmt->close();
        }else{
            header('location: account.php?error=Nie udało sięzmienić hasła');
        }
    }
}

if(isset($_SESSION['user']['logged_in'])){
    $user_id = $_SESSION['user']['id'];

    $stmt = $connection->prepare("SELECT `order`.*, `user`.client_ID, user.first_name, user.last_name, `status`.status_type
                                FROM `order`
                                LEFT JOIN `user` ON user.client_ID = `order`.client_ID
                                INNER JOIN `status` ON `status`.status_ID = `order`.status_ID
                                WHERE `user`.client_ID = ?
                                GROUP BY `order`.order_ID
                                ORDER BY `order`.order_ID DESC ;");
    $stmt->bind_param('i',$user_id);
    $stmt->execute();

    $users_orders = $stmt->get_result();
    $stmt->close();

}
$counter = 0;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/cart.css"/>
    <link rel="stylesheet" href="assets/css/login_v2.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- account -->
    <section id="account-section">
        <div class="container">
            <div class="text-container">
                <p style="color: green;"><?php if(isset($_GET['register_succes'])){ echo $_GET['register_succes'];}?></p>
                <p style="color: green;"><?php if(isset($_GET['login_succes'])){ echo $_GET['login_succes'];}?></p>
                <h3>Informacje o koncie</h3>
                <hr>
                <div class="account-info">
                    <p>Imię i nazwisko: <span><?php echo $_SESSION['user']['firstname'];?> <?php  echo $_SESSION['user']['lastname'];?></span></p>
                    <p>Email: <span><?php echo $_SESSION['user']['email']?></span></p>
                    <p><a href="#orders" id="order-btn">Twoje zamówienia</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Wyloguj się</a></p>
                </div>
            </div>

            <div class="form-container">
                <form id="account-form" action="account.php" method="POST">
                    <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                    <p style="color: green;"><?php if(isset($_GET['message'])){ echo $_GET['message'];}?></p>
                    <h3>Zmień hasło</h3>
                    <hr>
                    <div class="form-group">
                        <label for="">Hasło</label>
                        <input type="password" class="form-control" id="account-password" name="password" placeholder="Hasło" required>
                    </div>
                    <div class="form-group">
                        <label for="">Potwierdź hasło</label>
                        <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Hasło" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Zmień hasło" name="change_password" class="btn" id="change-pass-btn">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- orders -->
    <section id=orders class="container">
        <div class="title">
            <h2>Twoje zamówienia</h2>
            <hr>
        </div>
        <table>
            <tr>
                <th>ID zamówienia</th>
                <th>Koszt całkowity</th>
                <th>Status</th>
                <th>Data</th>
            </tr>
            <?php while($row = $users_orders->fetch_assoc()){ $counter++;?>
                <tr>
                    <td>
                        <span><?php echo $row['order_ID']; ?></span>
                    </td>
                    <td>
                        <span><?php echo $row['final_cost']; ?> zł</span>
                    </td>
                    <td>
                        <span><?php echo $row['status_type']; ?></span>
                    </td>
                    <td>
                        <span><?php echo $row['order_date']; ?></span>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <p style="color: red;"><?php if($counter<1){ echo "Nie złożyłeś jeszcze żadnych zamówień";}?></p>
    </section>



    <?php include('layouts/cookie_container.php'); ?>
    <?php include('layouts/footer.php'); ?>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>