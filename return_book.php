<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Return Books</title>
        <link rel="stylesheet" href="return_book.css">
    </head>
    <body>
        <?php
            // MYSQLi connection
            $conn = new mysqli("localhost", "root", "", "LibSys");
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }

            $user_id = $_SESSION["user_id"];

            echo "<table><tr><th>Book ID</th><th>Name</th><th>Author</th><th>Rating</th><th>Borrower ID</th></tr>";
            $select = "SELECT * FROM books";
            $result = $conn->query($select);
            if($result->num_rows>0) 
            {
                while($row = $result->fetch_assoc())
                {
                    if($user_id===$row["borrower_id"])
                    {
                        echo "<tr>";
                        echo "<td>".$row["book_id"]."</td>"."<td>".$row["book_name"]."</td>"."<td>".$row["author"]."</td>"."<td>".$row["avg_rating"]."</td>"."<td>".$row["borrower_id"]."</td>";
                        echo "</tr>";
                    } 
                }
            }
            echo "</table>";
        ?>
        <form action="return_book.php" method="post" onsubmit="return true;"> 
            <input type="text" required name="book_id" id="book_id" placeholder="Enter Book ID of book to return">
            <label for="book_id" id="book_id_msg"></label>
            <input type="submit" value="Borrow" name="submit">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $book_id=$_GET["book_id"];
                $user_id = $_SESSION["user_id"];

                // MYSQLi connection
                $conn = new mysqli("localhost", "root", "", "LibSys");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                // Checking if user has borrowed books
                $select = "SELECT num_borrowed FROM users WHERE user_id=".(string)$user_id;
                $result = $conn->query($select);
                if($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        $num_borrowed=$row["num_borrowed"];
                        if($num_borrowed==0)
                        {
                            exit;
                        }
                    }
                }
                
                // Verifying that book
                $select = "SELECT * FROM borrowings WHERE borrowed_id=? AND borrower_id=?";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("ii", $book_id, $user_id);
                $result = $stmt->execute();
                if($result>0) 
                {
                    $delete = "DELETE FROM borrowings WHERE borrowed_id=".(string)$book_id."AND borrower_id=".(string)$user_id;
                    $result = $conn->query($delete);

                    $update_books = "UPDATE books SET borrower_id=NULL WHERE book_id=".(string)$book_id;
                    $conn->query($update_books);
                    
                    $update_users = "UPDATE users SET num_borrowed=".(string)($num_borrowed-1)." WHERE user_id=".(string)$user_id;
                    $conn->query($update_users);
                }

                $stmt->close();
                $conn->close();

                header("Location: /LibSys/user_dashboard.php");
                exit;
            }
        ?>
    </body>
</html>
