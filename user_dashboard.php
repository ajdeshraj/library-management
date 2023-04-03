<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Dashboard</title>
    </head>
    <body>
        <a href = "borrow_book.php">Borrow Books</a>
        <br>
        <a href = "return_book.php">Return Books</a>
        <br>
        <a href = "logout.php">Logout</a>
    </body>
    <?php
        echo $_SESSION["user_id"].". ";
        echo "Dashboard for ".$_SESSION["username"];
    ?>
</html>