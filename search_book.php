<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Books</title>
    </head>
    <body>
        <form action="search_book.php" method="post">
            <label for="search_term" id="search_label">Enter Book or Author Name</label>
            <input type="text" id="search_term" name="search_term">
            <input type="submit" id="submit" name="submit" value="Search"> 
        </form>
        <?php
            if(isset($_POST['submit']))
            {
                $servername = "localhost";
                $username = "root";
                $password = "Factoid-Suds-Tavern3";
                $dbname = "library";
                
                $search_text = $_POST["search_term"];
                $search_text = strtoupper($search_text);
                $search_text = "%".$search_text."%";
                // echo $search_text."<br>";
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $select = "SELECT title, author FROM book WHERE title LIKE ? OR author LIKE ?";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("ss", $search_text, $search_text);
                $stmt->execute();

                $result = $stmt->get_result();
                
                if ($result->num_rows > 0)
                {
                    echo "<table border='1'>
                    <tr><th>Book Title</th><th>Author</th><th>Rating</th></tr>";
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>".$row["title"]."</td>";
                        echo "<td>".$row["author"]."</td>";
                        echo "<td>".$row["avg_rating"]."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else
                {
                    echo "No Results Found<br>";
                }

                $conn->close();
            }
        ?>
    </body>
</html>