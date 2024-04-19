<?php

session_start();

include('../server/connection.php');

if(isset($_SESSION['admin']['logged_in'])){
    header('location: index.php');
    exit;
}else if(isset($_POST['login_btn'])){
    $email = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
    $password = md5(trim(htmlspecialchars(htmlspecialchars(($_POST['password'])))));
    $a = "admin";

    $stmt = $connection->prepare("SELECT client_ID,first_name,last_name,`login`,`password` FROM user 
                                WHERE `login` = ? AND `password` = ? AND `role` = ? LIMIT 1");
    $stmt->bind_param('sss',$email,$password,$a);
    if($stmt->execute()){
        $stmt->bind_result($user_id,$user_firstname, $user_lastname, $user_email, $user_password);
        $stmt->store_result();
        if($stmt->num_rows() == 1){
            $_SESSION['admin']['logged_in'] = true;

            header('location: index.php?login_succes=Logowanie powiodło się');

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
    <link rel="stylesheet" href="assets/scss/style.css"/>
    <link rel="stylesheet" href="assets/scss/login.css"/>
</head>
<body>


    <!-- login -->
    <section id="login-section">
        <div class="container">
            <h2>Logowanie</h2>
        </div>
        <div class="container">
            <form id="login-form" action="/SklepZZegarkami/admin/login.php" method="POST" >
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
            </form>
        </div>
    </section>




    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>