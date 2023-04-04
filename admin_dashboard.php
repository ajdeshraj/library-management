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
    <title>Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard_css.css">
</head>
    <body>
        <div class="page-wrapper">
            <div class="heading">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="row">
                <div class="column">
                    <a href="add_book.php">
                        <h2>Add Book</h2>
                        <h4>Add New Book to the Library</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="delete_book.php">
                        <h2>Delete Book</h2>
                        <h4>Delete Existing Book from the Library</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="lib_dues_collect.php">
                        <h2>Collect Library Dues</h2>
                        <h4>Check and Collect Any Outstanding Library Dues</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="logout.php">
                        <h2>Logout</h2>
                        <h4>Logout and Return to Welcome Page</h4>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>