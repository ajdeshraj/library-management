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
                $username=$_GET["username"];
                $password=$_GET["password"];
                $num_borrowed=0;

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $select = "SELECT user_id, username, password FROM users";
                $result = $conn->query($select);
                if ($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        if($row["username"]===$username&&$row["password"]===$password)
                        {
                            $_SESSION["user_id"] = $row["user_id"];
                            $stmt->close();
                            $conn->close();
                            header("Location: /user_dashboard.php");
                            exit;
                        }
                    }
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
