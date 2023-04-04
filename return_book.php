<?php
    session_start();
?>
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
        <a href = "user_dashboard.php">User Dashboard</a>
        <?php
            // MYSQLi connection
            $conn = new mysqli("localhost", "root", "", "LibSys");
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
                echo "<table><tr><th>Book ID</th><th>Name</th><th>Author</th><th>Rating</th><th>Borrower ID</th></tr>";
                while($row = $result->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$row["book_id"]."</td>"."<td>".$row["book_name"]."</td>"."<td>".$row["author"]."</td>"."<td>".$row["avg_rating"]."</td>"."<td>".$row["borrower_id"]."</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        ?>
        <form method="post" onsubmit="return validate();"> 
            <input type="text" required name="book_id" id="book_id" placeholder="Enter Book ID of book to return">
            <label for="book_id" id="book_id_msg"></label>
            <input type="text" required name="rating" id="rating" placeholder="Enter rating for the book from 1 to 10">
            <label for="rating" id="rating_msg"></label>
            <input type="submit" value="Return" name="submit">
        </form>
        <script>
            function validate()
            {
                const rating = document.getElementById("rating").value;
                if((!rating<=10) || (!rating>=1))
                {
                    document.getElementById("username_msg").innerHTML="Invalid username";
                    document.getElementById("username_msg").style.color=red;
                    wait(1000);
                    return false;
                }

                // Password Validation
                const pw_pat = /(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*\W).{8,20}/
                if(!password1.match(pw_pat))
                {
                    document.getElementById("password1_msg").innerHTML="Invalid Password";
                    document.getElementById("password1_msg").style.color=red;
                    wait(1000);
                    return false;
                }
                else if(!(password1===password2))
                {
                    document.getElementById("password1_msg").innerHTML="Passwords do not match";
                    document.getElementById("password1_msg").style.color=red;
                    wait(1000);
                    return false;
                }
            
                return true;
            }          
        </script>
        <?php
            if(isset($_POST['submit']))
            {
                $book_id=$_POST["book_id"];
                $rating=$_POST["rating"];
                $user_id = $_SESSION["user_id"];

                // MYSQLi connection
                $conn = new mysqli("localhost", "root", "", "LibSys");
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                // Checking if user has borrowed books
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
                        if($num_borrowed==0)
                        {
                            exit;
                        }
                    }
                }
                
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
