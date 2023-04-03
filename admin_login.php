<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <form method="post"> 
            <input type="text" required name="admin_id" id="admin_id" placeholder="Admin ID">
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
                $admin_id=$_POST["admin_id"];
                $password=$_POST["password"];

                // MYSQLi connections
                $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                // Authenticating and redirecting
                $select = "SELECT * FROM admins WHERE admin_id = ? AND pw = ?";
                $stmt = $conn->prepare($select); 
                $stmt->bind_param("ss", $admin_id, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        $_SESSION["admin_id"] = $row["admin_id"];
                        $stmt->close();
                        $conn->close();
                        header("Location: admin_dashboard.php");
                        exit;
                    }
                }
                else
                {
                    $stmt->close();
                    $conn->close();
                    echo("Invalid Login Details");
                    session_unset();
                    session_destroy();
                }
            }
        ?>
    </body>
</html>