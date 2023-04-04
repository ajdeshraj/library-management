<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin Login</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="login_css.css">
    </head>
    <body>
        <div class="form" align="center">
            <h2>Admin Login</h2>
            <form method="post"> 
                <input type="text" required name="admin_id" id="admin_id" placeholder="Admin ID" class="text_form">
                <label for="username" id="username_msg"></label>
                <input type="password" required name="password" id="password" placeholder="Password" class="text_form">
                <label for="password" id="password_msg"></label>
                <input type="submit" value="Login" name="submit" class="button">
            </form>
        </div>
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
                    echo("<p>Invalid Login Details</p>");
                    session_unset();
                    session_destroy();
                }
            }
        ?>
    </body>
</html>