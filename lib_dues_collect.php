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
        <title>Library Dues Collection</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="table_pages_css.css">
    </head>
    <body>
        <ul>
            <li><a href = "admin_dashboard.php">Admin Dashboard</a></li>
        </ul>
        <form method="post">
            <input type="text" name="book_id" placeholder="Book ID" class="text_form">
            <input type="submit" name="submit" value="Submit" class="button">
        </form>
        <?php
            if (isset($_POST["submit"]))
            {
                $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }
                $select = "SELECT * FROM borrowings WHERE dor < curdate()";
                $result = $conn->query($select);
                if ($result->num_rows > 0)
                {
                    echo "<table border='1'>
                    <thead>
                    <tr>
                    <th>Book ID</th>
                    <th>User ID</th>
                    <th>Date of Borrow</th>
                    <th>Date of Return</th>
                    <th>Overdue Fees</th>
                    </tr>
                    </thead>
                    <tbody>";
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>".$row["borrowed_id"]."</td>";
                        echo "<td>".$row["borrower_id"]."</td>";
                        echo "<td>".$row["dob"]."</td>";
                        echo "<td>".$row["dor"]."</td>";
                        $date_diff = "SELECT DATEDIFF(curdate(), dor) AS date_diff FROM borrowings WHERE borrowed_id = ".$row["borrowed_id"];
                        $result_diff = $conn->query($date_diff);
                        $row_diff = $result_diff->fetch_assoc();
                        $diff_days = $row_diff["date_diff"];
                        $fees = $diff_days*10;
                        echo "<td>".$fees."</td>";
                    }
                    echo "</tbody>
                    </table>";
                }
                else
                {
                    echo "<p>0 results</p>";
                    $conn->close();
                    header("Location: admin_dashboard.php");
                    exit;
                }

                $book_id = $_POST["book_id"];
                $delete_query = "DELETE FROM borrowings WHERE borrowed_id = ?";
                $stmt = $conn->prepare($delete_query);
                $stmt->bind_param("i", $book_id);
                $stmt->execute();
                echo "<p>Book Successfully Returned with Pending Dues Paid</p>";
                $conn->close();
                // sleep(5);
                // header("Location: admin_dashboard.php");
                // exit;
            }
        ?>
    </body>
</html>