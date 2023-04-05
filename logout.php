<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logged out</title>
    </head>
    <body>
        <?php
            // echo $_SESSION["user_id"];
            session_unset();
            session_destroy();
            // echo $_SESSION["user_id"];
        
            echo "<p>Logged out successfully!</p>";
            header("Location: welcome_page.html");
        ?>
    </body>
</html>