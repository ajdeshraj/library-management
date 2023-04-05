<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Book</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <ul>
            <li><a href = "admin_dashboard.php">Admin Dashboard</a></li>
        </ul>
        <form method="post" onsubmit="return validate()">
            <input type="text" id="title" name="title" placeholder="Book Title" class="text_form" required>
            <label for="title" id="title_msg"></label>
            <input type="text" id="author" name="author" placeholder="Author of Book" class="text_form" required>
            <label for="author" id="author_msg"></label>
            <input type="submit" id="submit" name="submit" value="Submit" class="button">
        </form>
        <script>
            function validate()
            {
                let title = document.getElementById("title");
                title = title.trim();
                if (title.length == 0)
                {
                    document.getElementById("title_msg").innerHTML = "Invalid Title"
                    return false
                }
                let author = document.getElementById("author");
                author = author.trim();
                if (author.length == 0)
                {
                    document.getElementById("author_msg").innerHTML = "Invalid Author"
                    return false
                }
                return true
            }
        </script>
        <?php
            if (isset($_POST['submit']))
            {
                $book_title = $_POST["title"];
                $book_author = $_POST["author"];

                $servername = "localhost";
                $username = "root";
                $password = "Factoid-Suds-Tavern3";
                $dbname = "library";

                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if($conn->connect_error)
                {
                    die("Connection failed: ".$conn->connect_error."<br>");
                }

                $id_query = "SELECT max(book_id) as max_id FROM books";
                $result = $conn->query($id_query);
                if ($result->num_rows > 0)
                {
                    while ($row = $result->fetch_assoc())
                    {
                        $book_id = $row["max_id"]+1;
                    }
                }

                $insert_query = "INSERT INTO books (book_id, book_name, author) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("iss", $book_id, $book_title, $book_author);
                $stmt->execute();

                // header("Location: admin_dashboard.php");
                // exit;
            }
        ?>
    </body>
</html>