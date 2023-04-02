<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Books</title>
    </head>
    <body>
        <form action="search_book.html" method="post">
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
                
                $search_text = $_GET["search_term"];
                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $select = "SELECT title, author FROM book";
                $result = $conn->query($select);
                
                if ($result->num_rows > 0)
                {
                    echo "<table border='1'>
                    <tr><th>Book Title</th><th>Author</th><th>Rating</th></tr>";
                    while ($row = $result->fetch_assoc())
                    {
                        $upper_title = strtoupper($row["title"]);
                        $upper_author = strtoupper($row["author"]);
                        if (similar_text($upper_title, $search_text) > 50.0 || similar_text($upper_author, $search_text) > 50.0)
                        {
                            echo "<tr>";
                            echo "<td>".$row["title"]."</td>";
                            echo "<td>".$row["author"]."</td>";
                            echo "<td>".$row["avg_rating"]."</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                }

                $select->close();
                $conn->close();
            }
        ?>
    </body>
</html>