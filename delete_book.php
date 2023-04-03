<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Delete Book</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "Factoid-Suds-Tavern3";
            $dbname = "library";

            $conn = new mysqli($servername, $username, $password, $dbname);
    
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }

            $select = "SELECT * FROM books";
            $result = $conn->query($select);
            if($result->num_rows>0) 
            {
                echo "<table border='1'>
                    <tr>
                    <th>Book ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Rating</th>
                    </tr>";
                while($row = $result->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$row["book_id"]."</td>"."<td>".$row["book_name"]."</td>"."<td>".$row["author"]."</td>"."<td>".$row["avg_rating"]."</td>"."<td>";
                    echo "</tr>";
                }
                echo "</table>";

                $conn->close();
            }
        ?>
        <form method="post">
            <input type="text" id="book_del_id" name="book_del_id" placeholder="Book ID of Book to be Deleted" required>
            <label for="book_del_id" id="book_del_msg"></label>
            <input type="submit" id="submit" name="submit" value="Delete">
        </form>
        <?php
            if (isset($_POST['submit']))
            {
                $book_id = $_POST['book_del_id'];
                
                $servername = "localhost";
                $username = "root";
                $password = "Factoid-Suds-Tavern3";
                $dbname = "library";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $delete = "DELETE FROM book WHERE book_id = ?";
                $stmt = $conn->prepare($delete);
                $stmt->bind_param("i", $book_id);
                $stmt->execute();
                
                echo "Book Deleted";
                stmt->close();
                conn->close();

                header("Location: admin_dashboard.php");
                exit;
            }
        ?>
    </body>
</html>