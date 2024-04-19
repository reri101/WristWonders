<?php

session_start();

include('server/connection.php');

if(isset($_SESSION['user']['logged_in'])){
    header('location: account.php');
    exit;
}else if (isset($_POST['register'])) {
    
    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function isValidPassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password);
    }

    $firstname = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['name'])));
    $lastname = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['lastname'])));
    $email = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
    $city = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['city'])));
    $post_code = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['post_code'])));
    $street = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['street'])));
    $phone_number = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['phone_number'])));
    $password = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['password'])));
    $confirmPassword = trim(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['confirmPassword'])));

    if (!isValidEmail($email)){
        header('location: registration.php?error=Nieporpawny email');
    }else if (!isValidPassword($password)){
        header('location: registration.php?error=Haslo musi zawierać przynajmniej jedną: małą literę, dużą literę, cyfrę');
    }else if (strlen($password) < 8){
        header('location: registration.php?error=haslo musi skladac sie z przynajmniej 8 znakow');
    }else if ($password != $confirmPassword){
        header('location: registration.php?error=Hasła nie są identyczne');
    }else if ($phone_number > 999999999 || $phone_number <= 99999999){
        header('location: registration.php?error=Nieporpawny numer telefonu');
    }else {
        $stmt = $connection->prepare("SELECT count(*) FROM contact WHERE e_mail_address=?;");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($num_rows);
        $stmt->fetch();
        $stmt->close();

        if ($num_rows != 0)
            header('location: registration.php?error=uzytkownik o takich danych juz istnieje');
        else {
            $stmt1 = $connection->prepare("INSERT INTO `contact` (phone_number,e_mail_address) VALUES (?,?);");
            $stmt1->bind_param('is', $phone_number, $email);
            $stmt1->execute();
            $contact_id = $stmt1->insert_id;
            $stmt1->close();

            $stmt2 = $connection->prepare("INSERT INTO `address` (town,postcode,street) VALUES (?,?,?);");
            $stmt2->bind_param('sss', $city, $post_code, $street);
            $stmt2->execute();
            $address_id = $stmt2->insert_id;
            $stmt2->close();

            $stmt3 = $connection->prepare("INSERT INTO user (address_ID ,contact_ID ,first_name,last_name,`login`,`password`) VALUES (?,?,?,?,?,?);");
            $stmt3->bind_param('iissss', $address_id, $contact_id, $firstname, $lastname, $email, md5($password));
            if($stmt3->execute()){
                $user_id = $stmt3->insert_id;

                $_SESSION['user']['id'] = $user_id;
                $_SESSION['user']['firstname'] = $firstname;
                $_SESSION['user']['lastname'] = $lastname;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['city'] = $city;
                $_SESSION['user']['post_code'] = $post_code;
                $_SESSION['user']['street'] = $street;
                $_SESSION['user']['phone_number'] = $phone_number;
                $_SESSION['user']['logged_in'] = true;

                $stmt3->close();
                header('location: account.php?register_succes=Logowanie powiodło się');
            } else{
                header('location: registration.php?error=nie udało sięutworzyć konta');
            }
        }
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
    <link rel="stylesheet" href="assets/css/login_v2.css"/>
    <link rel="stylesheet" href="assets/css/register_v.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- register -->
    <section id="register-section">
        <div class="container">
            <h2>Rejestracja</h2>
            <hr>
        </div>
        <div class="container">
            <form id="register-form" action="/SklepZZegarkami/registration.php" method="POST">
                <p style="color: red;"><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group">
                    <label>Imię</label>
                    <input type="text" id="register-name" name="name" placeholder="Imię" required>
                </div>
                <div class="form-group">
                    <label>Nazwisko</label>
                    <input type="text" id="register-lastname" name="lastname" placeholder="Nazwisko" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="register-email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label>Miasto</label>
                    <input type="text" id="register-city" name="city" placeholder="Miasto" required>
                </div>
                <div class="form-group">
                    <label>Kod pocztowy</label>
                    <input type="text" id="register-post-code" name="post_code" placeholder="Kod pocztowy" required>
                </div>
                <div class="form-group">
                    <label>Ulica</label>
                    <input type="text" id="register-street" name="street" placeholder="Ulica" required>
                </div>
                <div class="form-group">
                    <label>Numer telefonu</label>
                    <input type="text" id="register-phone-number" name="phone_number" placeholder="Numer telefonu" required>
                </div>
                <div class="form-group">
                    <label>Hasło</label>
                    <input type="password" id="register-password" name="password" placeholder="Hasło" required>
                </div>
                <div class="form-group">
                    <label>Potwierdź hasło</label>
                    <input type="password" id="register-confirm-password" name="confirmPassword" placeholder="Potwierdź hasło" required>
                </div>
                <div class="form-group">
                    <input type="submit" id="register-btn" name="register" value="Zarejestruj się">
                </div>
                <div class="form-group">
                    <a id="login-url" href="/SklepZZegarkami/login.php">Czy posiadasz już konto? Zaloguj się</a>    
                </div>
            </form>
        </div>
    </section>



    <?php include('layouts/footer.php'); ?>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>