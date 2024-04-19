<?php 

    session_start();
    include('../server/connection.php');

    if(!isset($_SESSION['admin']['logged_in'])){
        header('location: login.php');
        exit;
    }

    if(isset($_POST['edit_status_btn'])){
        $order_id = $_POST['order_id'];
        $selected_status_id = $_POST['order_status'];
        
        $stmt_order = $connection->prepare("UPDATE `order` SET status_ID = ? WHERE order_ID = ?");
        $stmt_order->bind_param('ii', $selected_status_id,$order_id);
        if($stmt_order->execute()){
            $stmt_order->close();
            header('Location: orders.php?status=success');
            exit;
        }else{
            header('Location: orders.php?status=error');
            exit;
        }
        
    }


    $stmt_all_orders = $connection->prepare("SELECT `order`.*, `user`.client_ID, user.first_name, user.last_name, `status`.status_type
                                        FROM `order`
                                        LEFT JOIN `user` ON user.client_ID = `order`.client_ID
                                        INNER JOIN `status` ON `status`.status_ID = `order`.status_ID
                                        GROUP BY `order`.order_ID
                                        ORDER BY `order`.order_ID DESC");
    $stmt_all_orders->execute();
    $all_orders = $stmt_all_orders->get_result();
    $stmt_all_orders->close();


    $stmt_status = $connection->prepare("SELECT * FROM `status` ");
    $stmt_status->execute();
    $order_statuses = $stmt_status->get_result();
    $stmt_status->close();
    $order_statuses_array = array();
    while ($tmp_row = $order_statuses->fetch_assoc()) {
        $order_statuses_array[] = $tmp_row;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="assets/scss/style.css"/>
    <link rel="stylesheet" href="assets/scss/orders.css"/>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <main>
        <?php include('includes/navbar.php'); ?>


        <div class="container orders">
            <div class="card">
                <div class="card-header formses-header">
                    <h3>Zamówienia</h3>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Łączna cena</th>
                            <th>Kupujący</th>
                            <th>Data złożenia</th>
                            <th>Status</th>
                            <th>Edytuj status</th>
                        </tr>

                        <?php while($row = $all_orders->fetch_assoc()){ ?>
                            <tr>
                                <form action="/SklepZZegarkami/admin/orders.php" method="POST">
                                    <td><?php echo $row['order_ID'];?></td>
                                    <td><?php echo $row['final_cost']; ?> zł</td>
                                    <td><?php echo $row['client_ID']; ?>. <?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['order_date']; ?></td>
                                    <td>
                                        <select id="status-method" name="order_status" value="<?php echo $row['status_type']; ?>" required>
                                            <option value="<?php echo $row['status_ID']; ?>"><?php echo $row['status_type']; ?> (aktualny)</option>
                                            <?php foreach ($order_statuses_array as $status) { if($status['status_type'] != $row['status_type']) { ?>
                                            
                                                <option value="<?php echo $status['status_ID']; ?>"><?php echo $status['status_type']; ?></option>

                                            <?php }}?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="edit_status_button center">
                                            <input type="hidden" name="order_id" value="<?php echo $row['order_ID'];?>">
                                            <input type="submit" class="edit btn" id="add-btn" name="edit_status_btn" value="Potwierdź zmiany">
                                        </div>
                                    </td>                 
                                </form>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
            
        </div>





    </main>


    <script src="assets/js/main.js"></script>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var status = urlParams.get('status');

        if (status === 'success') {
            showPopup('Status został zmieniony pomyślnie ✔', true);
        } else if (status === 'error'){
            showPopup('Nie udało sięzmienić statusu ❌', false);
        }
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
