<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <form action="register.php" method="post" onsubmit="return validate();"> 
            <input type="text" required name="username" id="username" placeholder="Username">
            <label for="username" id="username_msg"></label>
            <br>
            <input type="password" required name="password1" id="password1" placeholder="Password">
            <label for="password" id="password1_msg"></label>
            <br>
            <input type="password" required name="password2" id="password2" placeholder="Re-enter Password"> 
            <br>
            <input type="submit" value="Register" name="submit">
        </form>
        <script>
            function validate()
            {
                const username = document.getElementById("username").value;
                const password1 = document.getElementById("password1").value;
                const password2 = document.getElementById("password2").value;

                // Username validation
                const un_pat = /^[a-zA-Z][a-zA-Z0-9]{,20}$/
                if(!username.match(un_pat))
                {
                    document.getElementById("username_msg").innerHTML="Invalid username";
                    document.getElementById("username_msg").style.color=red;
                    return false;
                }

                // Password Validation
                const pw_pat = /(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*\W).{8,20}/
                if(!password1.match(pw_pat))
                {
                    document.getElementById("password1_msg").innerHTML="Invalid Password";
                    document.getElementById("password1_msg").style.color=red;
                    return false;
                }
                else if(!(password1===password2))
                {
                    document.getElementById("password1_msg").innerHTML="Passwords do not match";
                    document.getElementById("password1_msg").style.color=red;
                    return false;
                }
            
                return true;
            }          
        </script>
        <?php
            if(isset($_POST['submit']))
            {
                $username=$_GET["username"];
                $password=$_GET["password1"];
                $num_borrowed=0;

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $select = "SELECT user_id, username FROM users";
                $result = $conn->query($select);
                $user_id = 1;
                if ($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        // Check if username already exists
                        if($row["username"]===$username)
                        {
                            echo "Username already taken.<br>";
                            return;
                        }

                        // Automatically assign User ID
                        if($row["user_id"]>$user_id)
                        {
                            $user_id=$row["user_id"]+1;
                        }
                    }
                }

                $_SESSION["user_id"] = $user_id;

                $stmt = $conn->prepare("INSERT INTO users (user_id, username, password, num_borrowed) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("issi", $user_id, $username, $password, $num_borrowed);
                $stmt->execute();
            
                $stmt->close();
                $conn->close();

                header("Location: /user_dashboard.php");
                exit;
            }
        ?>
    </body>
</html>