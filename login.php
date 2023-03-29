<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <form action="login.php" method="post" onsubmit="return validate();"> 
            <input type="text" required name="username" id="username" placeholder="Username">
            <label for="username" id="username_msg"></label>
            <br>
            <input type="password" required name="password" id="password" placeholder="Password">
            <label for="password" id="password_msg"></label>
            <br>
            <input type="submit" value="Login" name="submit">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $username=$_POST["username"];
                $password=$_POST["password"];
                $num_borrowed=0;

                // MYSQLi connections
                $conn = new mysqli("localhost", "root", "", "LibSys");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                // Authenticating and redirecting
                $select = "SELECT user_id, username, pw FROM users WHERE username=? AND pw=?";
                $stmt = $conn->prepare($select); 
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows>0) 
                {
                    $_SESSION["user_id"] = $row["user_id"];
                    $stmt->close();
                    $conn->close();
                    header("Location: /LibSys/user_dashboard.php");
                    exit;
                }
                $stmt->close();
                $conn->close();
                echo("No such username.");
                session_unset();
                session_destroy(); 
            }
        ?>
    </body>
</html>