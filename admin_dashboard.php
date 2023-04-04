<?php
    session_start();
    // MYSQLi connections
    /* $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
    if($conn->connect_error)
    {
        die("Connection failed: ".$conn->connect_error."<br>");
    }
    $admin_query = "SELECT admin_id FROM admins";
    $result = $conn->query($admin_query);
    $row = $result->fetch_assoc();
    $admin = $row["admin_id"];
    if ($_SESSION["admin_id"] != $admin)
    {
        session_unset();
        session_destroy();
        $conn->close();
        header("Location: admin_login.php");
        exit;
    }
    conn->close(); */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        .card-holder 
        {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 90px;
        }
        .card-holder a 
        {
            border: 1px solid black;
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .card-holder a h2 
        {
            text-align: center;
        }
        .heading 
        {
            width: 100%;
            max-width: 100vw;
        }
        .heading h1 
        {
            text-align: center;
        }
        a 
        {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
    <body>
        <div>
            <div class="heading">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="card-holder">
                <a href="add_book.php">
                    <h2>Add Book</h2>
                </a>
                <a href="delete_book.php">
                    <h2>Delete Book</h2>
                </a>
                <a href="lib_dues_collect.php">
                    <h2>Collect Library Dues</h2>
                </a>
                <a href="logout.php">
                    <h2>Logout</h2>
                </a>
            </div>
        </div>
    </body>
</html>