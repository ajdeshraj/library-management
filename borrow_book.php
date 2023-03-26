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
        <?php
            $conn = new mysqli($servername, $username, $password, $dbname);
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
        ?>
        <form action="borrow_book.php" method="post" onsubmit="return validate();"> 
            <input type="text" required name="book_id" id="book_id" placeholder="Enter Book ID of book to borrow">
            <label for="book_id" id="book_id_msg"></label>
            <input type="submit" value="Borrow" name="submit">
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $book_id=$_GET["book_id"];
                $borrower_id = $_SESSION["user_id"];

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $select = "SELECT num_borrowed FROM users WHERE user_id=".(string)$borrower_id;
                $result = $conn->query($select);
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

                $select = "SELECT book_id, borrower_id FROM books";
                $result = $conn->query($select);
                $found = false;
                if($result->num_rows>0) 
                {
                    while($row = $result->fetch_assoc())
                    {
                        if($row["book_id"]===$book_id&&$row["borrower_id"]===NULL)
                        {
                            $found=true;
                            break;
                        }
                    }
                }

                if($found)
                {
                    $dob = date("Y/m/d");
                    $dor = date("Y/m/d", strtotime("-2 months"));
                    $stmt = $conn->prepare("INSERT INTO borrowings (borrower_id, borrowed_id, dob, dor) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iidd", $borrower_id, $book_id, $dob, $dor);
                    $stmt->execute();

                    $update_books = "UPDATE books SET borrower_id=".(string)$borrower_id." WHERE book_id=".(string)$book_id;
                    $conn->query($update_books);
                    
                    $update_users = "UPDATE users SET num_borrowed=".(string)$num_borrowed." WHERE user_id=".(string)$user_id;
                    $conn->query($update_users);
                }

                $stmt->close();
                $conn->close();

                header("Location: /user_dashboard.php");
                exit;
            }
        ?>
    </body>
</html>
