<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Books</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="table_pages_css.css">
    </head>
    <body>
        <ul>
            <li><a href = "user_dashboard.php">User Dashboard</a></li>
        </ul>
        <form action="search_book.php" method="post">
            <label for="search_term" id="search_label">Enter Book or Author Name</label>
            <input type="text" id="search_term" name="search_term" class="text_form">
            <input type="submit" id="submit" name="submit" value="Search" class="button"> 
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

                $select = "SELECT book_name, author FROM books WHERE book_name LIKE ? OR author LIKE ?";
                $stmt = $conn->prepare($select);
                $stmt->bind_param("ss", $search_text, $search_text);
                $stmt->execute();

                $result = $stmt->get_result();
                
                if ($result->num_rows > 0)
                {
                    echo "<table border='1'>
                        <thead>
                        <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Rating</th>
                        </tr>
                        </thead>
                        <tbody>";
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>".$row["book_name"]."</td>";
                        echo "<td>".$row["author"]."</td>";
                        echo "<td>".$row["avg_rating"]."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>
                        </table>";
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