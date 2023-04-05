<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Return Books</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="table_pages_css.css">
    </head>
    <body>
        <ul>
            <li><a href = "user_dashboard.php">User Dashboard</a></li>
        </ul>
        <?php
            // MYSQLi connection
            $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }
            $user_id = $_SESSION["user_id"];
            $select = "SELECT * FROM books where borrower_id=?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows>0) 
            {
                echo "<table>
                    <thead>
                    <tr>
                    <th>Book ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Rating</th>
                    <th>Borrower ID</th>
                    <th>Number of times Borrowed</th>
                    </tr>
                    </thead>
                    <tbody";
                while($row = $result->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$row["book_id"]."</td>"."<td>".$row["book_name"]."</td>"."<td>".$row["author"]."</td>"."<td>".$row["avg_rating"]."</td>"."<td>".$row["borrower_id"]."</td><td>".$row["num_borrowed"]."</td>";
                    echo "</tr>";
                }
            }
            echo "</tbody>
                </table>";
        ?>
        <form method="post" onsubmit="return validate();"> 
            <input type="text" required name="book_id" id="book_id" placeholder="Enter Book ID of book to return" class="text_form">
            <label for="book_id" id="book_id_msg"></label>
            <input type="text" required name="rating" id="rating" placeholder="Enter rating for the book from 1 to 10" class="text_form">
            <label for="rating" id="rating_msg"></label>
            <input type="submit" value="Return" name="submit" class="button">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $book_id=$_POST["book_id"];
                $rating=$_POST["rating"];
                $user_id = $_SESSION["user_id"];

                // MYSQLi connection
                $conn = new mysqli("localhost", "root", "Factoid-Suds-Tavern3", "library");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                // Checking if user has borrowed books
                $select = "SELECT num_borrowed, avg_rating FROM books WHERE book_id=? AND borrower_id=?";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("ii", $book_id, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows>0) 
                {
                    $row = $result->fetch_assoc();
                    $num_borrowed=$row["num_borrowed"];
                    $avg_rating=$row["avg_rating"];
                }
                if($num_borrowed!=0)
                {
                    $new_rating = (($num_borrowed*$avg_rating)+$rating)/($num_borrowed+1);
                }
                else
                {
                    $new_rating = $rating;
                }
                $num_borrowed = $num_borrowed + 1;
                // Verifying that book is borrowed
                $select = "SELECT * FROM borrowings WHERE borrowed_id=? AND borrower_id=? AND curdate()<=dor";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("ii", $book_id, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result>0) 
                {
                    $delete = "DELETE FROM borrowings WHERE borrowed_id=? AND borrower_id=?";
                    $stmt = $conn->prepare($delete);
                    $stmt->bind_param("ii", $book_id, $user_id);
                    $stmt->execute();

                    $update_books = "UPDATE books SET borrower_id=NULL WHERE book_id=?";
                    $stmt = $conn->prepare($update_books);
                    $stmt->bind_param("i", $book_id);
                    $stmt->execute();

                    $update_books = "UPDATE books SET avg_rating=? WHERE book_id=?";
                    $stmt = $conn->prepare($update_books);
                    $stmt->bind_param("ii", $new_rating, $book_id);
                    $stmt->execute();

                    $update_books = "UPDATE books SET num_borrowed=? WHERE book_id=?";
                    $stmt = $conn->prepare($update_books);
                    $stmt->bind_param("ii", $num_borrowed, $book_id);
                    $stmt->execute();

                    $update_ratings = "UPDATE ratings SET b".$book_id." WHERE user_id=".$user_id;
                    $result_ratings = $conn->query($update_ratings);

                    echo "<p>Successfully returned!</p>";
                }
                else
                {
                    echo "<p>No such book found. Contact admin for further details.</p>";
                }

                $stmt->close();
                $conn->close();
            }
        ?>
    </body>
</html>
