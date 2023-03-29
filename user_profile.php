<!DOCTYPE html>
<html>
    <head>
        <title>User Profile</title>
    </head>
    <body>
        <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
                
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }

            $current_borrows = "SELECT borrow.book_id, borrow.date_borrow, borrow.date_return, book.title, book.author FROM borrow, book WHERE borrow.user_id = ".user_id;
            $result_current_borrows = $conn->query($current_borrows);
            if ($result_current_borrows->num_rows > 0)
            {
                echo "<table border = '1'>
                <tr>
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Book Author</th>
                <th>Date of Borrow</th>
                <th>Date of Return</th>
                </tr>";
                while ($row = $result_current_borrows->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$row["book_id"]."</td>";
                    echo "<td>".$row["title"]."</td>";
                    echo "<td>".$row["author"]."</td>";
                    echo "<td>".$row["borrow_date"]."</td>";
                    echo "<td>".$row["return_date"]."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </body>
</html>