<?php

session_start();

include('server/connection.php');

if(isset($_SESSION['user']['logged_in'])){
    header('location: account.php');
    exit;
}else if(isset($_POST['login_btn'])){
    $email = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
    $password = md5(trim(htmlspecialchars(htmlspecialchars(($_POST['password'])))));

    $stmt = $connection->prepare("SELECT client_ID,first_name,last_name,`login`,`password`, `address`.street as street FROM user 
                                INNER JOIN `address` ON user.address_ID = `address`.address_ID 
                                WHERE `login` = ? AND `password` = ? LIMIT 1");
    $stmt->bind_param('ss',$email,$password);
    if($stmt->execute()){
        $stmt->bind_result($user_id,$user_firstname, $user_lastname, $user_email, $user_password, $user_street);
        $stmt->store_result();
        if($stmt->num_rows() == 1){
            $stmt->fetch();
            $stmt->close();
            $_SESSION['user']['id'] = $user_id;
            $_SESSION['user']['firstname'] = $user_firstname;
            $_SESSION['user']['lastname'] = $user_street;
            $_SESSION['user']['street'] = $user_lastname;
            $_SESSION['user']['email'] = $user_email;
            $_SESSION['user']['logged_in'] = true;

            header('location: account.php?login_succes=Logowanie powiodło się');

        }else{
            header('location: login.php?error=Weryfikacja konta niepowiodła się');
        }
    }else{
        header('location: login.php?error=Coś poszło nie tak');
    }

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/cart.css"/>
    <link rel="stylesheet" href="assets/css/login.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- login -->
    <section id="login-section">
        <div class="container">
            <h2>Logowanie</h2>
            <hr>
        </div>
        <div class="container">
            <form id="login-form" action="/SklepZZegarkami/login.php" method="POST" >
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="login-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label>Hasło</label>
                    <input type="password" id="login-password" name="password" placeholder="Hasło" required>
                </div>
                <div class="form-group">
                    <input type="submit" id="login-btn" name="login_btn" value="Zaloguj się">
                </div>
                <div class="form-group">
                    <a id="register-url" href="/SklepZZegarkami/registration.php">Nie posiadasz jeszcze konta? Zarejestruj się</a>    
                </div>
            </form>
        </div>
    </section>



    <?php include('layouts/footer.php'); ?>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>