<?php include('server/finalize_order.php'); ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamówienie - Podsumowanie</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/checkout.css"/>
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- order summarize -->
    <section id="order_sum">
        <h1>Podsumowanie zamówienia</h1>
        <hr>
        <div id="order-summary">
            <form method="POST" action="server/finalize_order.php">
                <div class="section">
                    <h4>Dane użytkownika</h4>
                    <label for="username">Imię i nazwisko:</label>
                    <input type="text" id="username" value="<?php echo $_SESSION['user']['firstname'] ?> <?php echo $_SESSION['user']['lastname'] ?>" readonly>

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" value="<?php echo $_SESSION['user']['email'] ?>" readonly>
                </div>

                <div class="section">
                    <h4>Dane dostawy</h4>
                    <label for="address">Adres dostawy:</label>
                    <input type="text" id="address" value="ul.<?php echo $_SESSION['user']['street'] ?> " readonly>
                    <!-- <textarea id="address" readonly>ul. </textarea> -->
                </div>

                <div class="section">
                    <h4>Podsumowanie kosztów</h4>
                    <label for="total-cost">Całkowity koszt zamówienia:</label>
                    <input type="text" id="total-cost" value="<?php echo $_SESSION['total']; ?> zł" readonly>
                </div>

                <div class="section">
                    <h4>Opcje dostawy</h4>
                    <label for="shipping-method">Wybierz metodę dostawy:</label>
                    <select id="shipping-method" name="shipping_method" required>
                        <?php include('server/get_shipping_method.php'); ?>
                        <?php while($row = $shipping_options->fetch_assoc()){ ?>
                        
                            <option value="<?php echo $row['shipping_ID']; ?>"><?php echo $row['shipping_type']; ?></option>

                        <?php }?>
                    </select>
                </div>
                <div class="section">
                    <h4>Opcje płatności</h4>
                    <label for="payment-method">Wybierz metodę płatności:</label>
                    <select id="payment-method" name="payment_method" required>
                        <?php include('server/get_payment_options.php'); ?>
                        <?php while($row = $payment_options->fetch_assoc()){ ?>
                        
                            <option value="<?php echo $row['payments_ID']; ?>"><?php echo $row['payments_type']; ?></option>

                        <?php }?>
                    </select>
                </div>

            
                <input type="submit" name="payment_btn" class="pay_btn btn" id="payment-btn" value="Opłać zamówienie">
            </form>
        </div>
    </section>
    


    <?php include('layouts/footer.php'); ?>

    <script src="assets/js/main.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
