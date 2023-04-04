<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Borrow Books</title>
        <link rel="stylesheet" href="borrow_book.css">
    </head>
    <body>
        <a href = "user_dashboard.php">User Dashboard</a>
        <?php
            // MYSQLi connection
            $conn = new mysqli("localhost", "root", "", "LibSys");
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }

            $select = "SELECT * FROM books";
            $result = $conn->query($select);
            echo "<table><tr><th>Book ID</th><th>Name</th><th>Author</th><th>Rating</th><th>Borrower ID</th></tr>";
            if($result->num_rows>0) 
            {
                while($row = $result->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$row["book_id"]."</td>"."<td>".$row["book_name"]."</td>"."<td>".$row["author"]."</td>"."<td>".$row["avg_rating"]."</td>"."<td>".$row["borrower_id"]."</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        ?>
        <form method="post" onsubmit="return true;"> 
            <input type="text" required name="book_id" id="book_id" placeholder="Enter Book ID of book to borrow">
            <label for="book_id" id="book_id_msg"></label>
            <input type="submit" value="Borrow" name="submit">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $book_id=$_POST["book_id"];
                $user_id = $_SESSION["user_id"];

                $conn = new mysqli("localhost", "root", "", "LibSys");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }
                $select = "SELECT count(borrowed_id) AS num_borrowed FROM borrowings WHERE borrower_id=?";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        $num_borrowed=$row["num_borrowed"];
                        if($num_borrowed==4)
                        {
                            exit;
                        }
                    }
                }

                # Verifying that book is not borrowed
                $select = "SELECT book_id FROM books WHERE book_id=? AND borrower_id=NULL";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("i", $book_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result>0) 
                {
                    $stmt = $conn->prepare("select curdate() as dob");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $dob = $row["dob"];

                    $stmt = $conn->prepare("SELECT DATE_ADD(curdate(), INTERVAL 14 DAY) AS dor");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $dor = $row["dor"];

                    $insert_borrowings="INSERT INTO borrowings (borrower_id, borrowed_id, dob, dor) VALUES (?, ?, '".$dob."', '".$dor."')";
                    $stmt = $conn->prepare($insert_borrowings);
                    $stmt->bind_param("ii", $user_id, $book_id);
                    $stmt->execute();

                    $update_books = "UPDATE books SET borrower_id=? WHERE book_id=?";
                    $stmt = $conn->prepare($update_books);
                    $stmt->bind_param("ii", $user_id, $book_id);
                    $stmt->execute();

                    echo "<p>Successfully borrowed!</p>";
                }
                $stmt->close();
                $conn->close();
            }
        ?>
    </body>
</html>
