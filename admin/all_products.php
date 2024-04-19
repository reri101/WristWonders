<?php 

    session_start();
    include('../server/connection.php');

    if(!isset($_SESSION['admin']['logged_in'])){
        header('location: login.php');
        exit;
    }

    include('functions/get_all_watches.php'); 

    if(isset($_GET['delete'])){
        $product_id = $_GET['delete'];
        $watch_id = $_GET['watch_id'];
        
        $stmt_remove_product = $connection->prepare("DELETE FROM product WHERE product_id = ?");
        $stmt_remove_product->bind_param("i", $product_id);
        if($stmt_remove_product->execute()){
            $stmt_remove_product->close();

            $stmt_remove_watch_category = $connection->prepare("DELETE FROM watch_category WHERE watch_ID = ?");
            $stmt_remove_watch_category->bind_param("i", $watch_id);
            if($stmt_remove_watch_category->execute()){
                $stmt_remove_watch_category->close();
    
                $stmt_remove_watch = $connection->prepare("DELETE FROM watch WHERE watch_ID = ?");
                $stmt_remove_watch->bind_param("i", $watch_id);
                if($stmt_remove_watch->execute()){
                    $stmt_remove_watch->close();
                    header('Location: all_products.php?status=success');
                    exit;
                }
                else{
                    header('Location: all_products.php?status=error');
                    exit;
                }
            }else{
                header('Location: all_products.php?status=error');
                exit;
            }
        }else{
            header('Location: all_products.php?status=error');
            exit;
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="assets/scss/style.css"/>
    <link rel="stylesheet" href="assets/scss/all_products.css"/>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <main>
        <?php include('includes/navbar.php'); ?>


        <div class="container">
            <div class="card">
                <div class="card-header formses-header">
                    <h3>Produkty</h3>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa</th>
                            <th>Zdjęcie</th>
                            <th>Cena</th>
                            <th>Kategoria</th>
                            <th>Edycja</th>
                            <th>Usuwanie</th>
                        </tr>

                        <?php while($row = $all_watches->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $row['product_ID'];?></td>
                            <td><?php echo $row['watch_name']; ?></td>
                            <td><img src="../<?php echo $row['image_first']; ?>" /></td>
                            <td><?php echo $row['price']; ?> zł</td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><a class="edit btn" href="edit_product.php?edit=<?php echo $row['product_ID'];?>&watch_id=<?php echo $row['watch_id']; ?>">Edytuj</a</td>
                            <td><a class="delete btn" href="all_products.php?delete=<?php echo $row['product_ID'];?>&watch_id=<?php echo $row['watch_id']; ?>">Usuń</a></td>
                        </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
            
        </div>





    </main>



    <script  src="assets/js/main.js"></script>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var status = urlParams.get('status');

        if (status === 'success') {
            showPopup('Zegarek został usunięty pomyślnie ✔', true);
        } else if (status === 'success_edit'){
            showPopup('Zegarek został zedytowany pomyślnie ✔', true);
        } else if (status === 'error'){
            showPopup('Usuwanie lub edytowanie zegarka nie powiodło się ❌', false);
        }
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
