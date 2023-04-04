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
        <link rel="stylesheet" href="login_css.css">
    </head>
    <body>
        <div class="form" align="center">
            <h2>User Login</h2>
            <form method="post" onsubmit="return validate();">
                <label class="wrap"> 
                    <input type="text" required name="username" id="username" placeholder="Username" class="text_form">
                    <label for="username" id="username_msg"></label>
                </label>
                <label class="wrap">
                    <input type="password" required name="password" id="password" placeholder="Password" class="text_form">
                    <label for="password" id="password_msg"></label>
                </label>
                <input type="submit" value="Login" name="submit" class="button">
            </form>
        <a href="register.php">New User? Register Here</a>
        </div>
        <?php
            if(isset($_POST['submit']))
            {
                $username=$_POST["username"];
                $password=$_POST["password"];
                $num_borrowed=0;

                // MYSQLi connections
                $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
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
                    while($row = $result->fetch_assoc())
                    {
                        $_SESSION["user_id"] = $row["user_id"];
                        $_SESSION["username"] = $row["username"];
                        $stmt->close();
                        $conn->close();
                        header("Location: user_dashboard.php");
                        exit;
                    }
                }
                else
                {
                    $stmt->close();
                    $conn->close();
                    echo("<p>Invalid Login Details</p>");
                    session_unset();
                    session_destroy();
                }
            }
        ?>
    </body>
</html>