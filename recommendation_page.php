<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Recommendation</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="table_pages_css.css">
    </head>
    <body>
        <ul>
            <li><a href = "user_dashboard.php">User Dashboard</a></li>
        </ul>
        <h2>Book Recommendations</h2>
        <?php
            // Function to push element into associative array
            function array_push_assoc($array, $key, $value)
            {
                $array[$key] = $value;
                return $array;
            }

            $servername = "localhost";
            $username = "root";
            $password = "Factoid-Suds-Tavern3";
            $dbname = "library";

            $conn = new mysqli($servername, $username, $password, $dbname);
                
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }
            
            // Query to get number of books
            $id_query = "SELECT count(book_id) as num_books FROM books";
            $result = $conn->query($id_query);
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $num_books = $row["num_books"]+1;
            }
            // echo $_SESSION["user_id"]."<br>";
            $user_ratings_query = "SELECT * FROM ratings WHERE user_id = ".$_SESSION["user_id"];
            $other_users_ratings_query = "SELECT * FROM ratings WHERE user_id != ".$_SESSION["user_id"];
            $result_user_ratings = $conn->query($user_ratings_query);
            $result_other_users_ratings = $conn->query($other_users_ratings_query);
            
            $user_common_ratings = array(); // Array to store ratings of the common books of current user
            $other_user_common_ratings = array();   // Array to store ratings of the common books of the other user
            $similarity_coefficient = array();  // Array to store similartiy coefficients

            $user_row = $result_user_ratings->fetch_assoc();
            if ($result_other_users_ratings->num_rows > 0)
            {   // echo $_SESSION["user_id"]."<br>";
                while ($other_users_row = $result_other_users_ratings->fetch_assoc())
                {
                    // echo $_SESSION["user_id"]."<br>";
                    // Resetting arrays for each combination of users
                    // unset($common_book_id);
                    // unset($user_common_ratings);
                    // unset($other_user_common_ratings);
                    array_diff($user_common_ratings, $user_common_ratings);
                    array_diff($other_user_common_ratings, $other_user_common_ratings);
                    // $user_common_ratings = array(); 
                    // $other_user_common_ratings = array(); 

                    for ($i = 0; $i < $num_books; $i++)
                    {   
                        if ($user_row["b".$i] != 0 && $other_users_row["b".$i] != 0)
                        {
                            // array_push($common_book_id, $i);
                            array_push($user_common_ratings, $user_row["b".$i]);
                            array_push($other_user_common_ratings, $other_users_row["b".$i]);
                        }
                    }
                    
                    // echo "Ratings".$user_common_ratings."<br>".$other_user_common_ratings."<br>";
                    // Calculating Cosine Similarity
                    $dot_product = 0;
                    $norm_user = 0;
                    $norm_other_user = 0;
                    
                    for ($i = 0; $i < sizeof($user_common_ratings); $i++)
                    {   
                        
                        $dot_product += $user_common_ratings[$i]*$other_user_common_ratings[$i];
                        $norm_user += $user_common_ratings[$i]*$user_common_ratings[$i];
                        $norm_other_user += $other_user_common_ratings[$i]*$other_user_common_ratings[$i];
                        
                    }
                    $norm_user = sqrt($norm_user);
                    $norm_other_user = sqrt($norm_other_user);

                    $sim = $dot_product/($norm_user*$norm_other_user);
                    // Pushing UserID => Similarity Coefficient
                    $similarity_coefficient = array_push_assoc($similarity_coefficient, $other_users_row["user_id"], $sim);           
                }
            }
            
            // echo $similarity_coefficient."<br>";
            // foreach($similarity_coefficient as $x => $x_value) {
            //     echo "Key=" . $x . ", Value=" . $x_value;
            //     echo "<br>";
            //   }

            $rec_ids = array();
            $result_other_users_ratings = $conn->query($other_users_ratings_query);
            if ($result_other_users_ratings->num_rows > 0)
            {   
                // echo "<script>console.log('".$result_other_users_ratings->fetch_assoc()."')</script>";
                while ($other_user_row = $result_other_users_ratings->fetch_assoc())
                {   
                    
                    $other_id = $other_user_row["user_id"];
                    
                    foreach ($similarity_coefficient as $id=>$sim_c)
                    {
                        
                        if ($other_id == $id && $sim_c > 0.4)
                        {
                            for ($i = 0; $i < $num_books; $i++)
                            {
                                if ($other_user_row["b".$i] > 6 && $user_row["b".$i] == 0 && !in_array($i, $rec_ids))
                                {
                                    array_push($rec_ids, $i);
                                }
                            }
                        }
                    }
                }
            }
            // echo $rec_ids."<br>";

            if (sizeof($rec_ids) > 0)
            {
                echo "<table border = '1'>
                    <thead>
                    <tr>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Book Author</th>
                    </tr>
                    </thead>
                    <tbody>";
                foreach ($rec_ids as $i)
                {
                    $rec_query = "SELECT * FROM books WHERE book_id = ".$i;
                    $result_rec = $conn->query($rec_query);
                    $row_recs = $result_rec->fetch_assoc();
                    echo "<tr>";
                    echo "<td>".$row_recs["book_id"]."</td>";
                    echo "<td>".$row_recs["book_name"]."</td>";
                    echo "<td>".$row_recs["author"]."</td>";
                    echo "</tr>";
                }
                echo "</tbody>
                    </table>";
            }
            else
            {
                echo "<p>No Recommendations Available At The Moment!</p>";
            }
            
            $conn->close();
        ?>
    </body>
</html>
