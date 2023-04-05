<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>User Dashboard</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="dashboard_css.css">
    </head>
    <body>
        <div class="page-wrapper">
            <div class="heading">
                <h1>User Dashboard</h1>
            </div>
            <div class="row">
                <div class="column">
                    <a href="borrow_book.php">
                        <h2>Borrow Book</h2>
                        <h4>Borrow Books from Library</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="return_book.php">
                        <h2>Return Book</h2>
                        <h4>Return and Rate Books to the Library</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="search_book.php">
                        <h2>Search Book</h2>
                        <h4>Search Available Books in the Library based on Author or Title</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="recommendation_page.php">
                        <h2>Book Recommendations</h2>
                        <h4>Receive Personalized Book Recommendations</h4>
                    </a>
                </div>
                <div class="column">
                    <a href="logout.php">
                        <h2>Logout</h2>
                        <h4>Logout and Return to Welcome Page</h4>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>