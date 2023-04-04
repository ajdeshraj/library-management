<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Recommendation</title>
    </head>
    <body>
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
            $id_query = "SELECT max(book_id) as max_id FROM books";
            $result = $conn->query($id_query);
            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc())
                {
                    $max_id = $row["max_id"]+1;
                }
            }

            $user_ratings_query = "SELECT * FROM ratings WHERE user_id = 0";// .$_SESSION["user_id"];
            $other_users_ratings_query = "SELECT * FROM ratings WHERE user_id != 0";//.$_SESSION["user_id"];
            $result_user_ratings = $conn->query($user_ratings_query);
            $result_other_users_ratings = $conn->query($other_users_ratings_query);
            
            $common_book_id = array();  // Array to store common books between users
            $user_common_ratings = array(); // Array to store ratings of the common books of current user
            $other_user_common_ratings = array();   // Array to store ratings of the common books of the other user
            $similarity_coefficient = array();  // Array to store similartiy coefficients
            $user_row = $result_user_ratings->fetch_assoc();
            if ($result_other_users_ratings->num_rows > 0)
            {
                while ($other_users_row = $result_other_users_ratings->fetch_assoc())
                {
                    // Resetting arrays for each combination of users
                    unset($common_book_id);
                    unset($user_common_ratings);
                    unset($other_user_common_ratings);

                    for ($i = 0; $i < $max_id; $i++)
                    {
                        if ($user_row["b".$i] != 0 && $other_users_row["b".$i] != 0)
                        {
                            array_push($common_book_id, $i);
                        }
                    }
                    foreach ($common_book_id as $id)
                    {
                        array_push($user_common_ratings, $user_row["b".$id]);
                        array_push($other_user_common_ratings, $other_users_row["b".$id]);
                    }

                    // Calculating Cosine Similarity
                    $dot_product = 0;
                    $norm_user = 0;
                    $norm_other_user = 0;
                    
                    for ($i = 0; $i < sizeof($user_common_ratings); $i++)
                    {
                        $dot_product += $user_common_ratings[i]*$other_user_common_ratings[i];
                        $norm_user += $user_common_ratings[i]*$user_common_ratings[i];
                        $norm_other_user += $other_user_common_ratings[i]*$other_user_common_ratings[i];
                    }
                    $norm_user = sqrt($norm_user);
                    $norm_other_user = sqrt($norm_other_user);

                    $sim = $dot_product/($norm_user*$norm_other_user);
                    // Pushing UserID => Similarity Coefficient
                    array_push_assoc($similarity_coefficient, $other_users_row["user_id"], $sim);                
                }
            }

            $rec_ids = array();
            if ($result_other_users_ratings->num_rows > 0)
            {
                while ($other_users_row = $result_other_users_ratings->fetch_assoc())
                {
                    $other_id = $other_users_row["user_id"];
                    foreach ($similarity_coefficient as $id=>$sim_c)
                    {
                        if ($other_id == $id && $sim_c > 0.4)
                        {
                            for ($i = 0; $i < $max_id; $i++)
                            {
                                if ($other_users_row["b".$i] > 6 && $user_row["b".$i] == 0 && in_array($i, $rec_ids))
                                {
                                    array_push($rec_ids, $i);
                                }
                            }
                        }
                    }
                }
            }
            if (sizeof($rec_ids) > 0)
            {
                echo "<table border = '1'
                    <tr>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>Book Author</th>
                    </tr>";
                foreach ($rec_ids as $i)
                {
                    $rec_query = "SELECT * FROM books WHERE book_id = ".$i;
                    $result_rec = $conn->query($rec_query);
                    while ($row_recs = $result_rec->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>".$row_recs["book_id"]."</td>";
                        echo "<td>".$row_recs["book_name"]."</td>";
                        echo "<td>".$row_recs["author"]."</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            }
            else
            {
                echo "No Recommendations Available At The Moment!<br>";
            }
            

            /*
            $other_ids = array();
            $similarity = array();

            $user_query = "SELECT * FROM ratings WHERE user_id = ".$_SESSION["user_id"];
            $result_user = $conn->query($user_query);

            $other_query = "SELECT * FROM ratings where user_id != ".$_SESSION["user_id"];
            $result_others = $conn->query($other_query);

            $user_row = $result_user->fetch_assoc();
            if ($result_others->num_rows > 0)
            {
                while ($other_rows = $result_others->fetch_assoc())
                {
                    $user_array = array();
                    $other_array = array();
                    for ($i = 0; $i < 100; $i++)
                    {
                        if ($user_row["b".$i] != 0 && $other_rows["b".$i] != 0)
                        {
                            array_push($user_array, $user_row["b".$i]);
                            array_push($other_array, $other_rows["b".$i]);                            
                        }
                    }

                    $dot_product = 0;
                    $norm_user = 0;
                    $norm_other  = 0;
                    for ($i = 0; $i < sizeof($user_array); $i++)
                    {
                        $dot_product += $user_array[i]*$other_array;
                        $norm_user += $user_array[i]*$user_array[i];
                        $norm_other += $other_array[i]*$other_array[i]; 
                    }
                    $norm_user = sqrt($norm_user);
                    $norm_other = sqrt($norm_other);

                    array_push($other_ids, $other_rows["user_id"]);
                    array_push($similarity, ($dot_product/($norm_other*$norm_user)));
                }
            }

            for ($i = 0; $i < sizeof($similarity); $i++)
            {
                if ($sim < 0.4)
                {
                    unset($other_ids[$i]);
                }
            }

            $recommendation = array();
            foreach ($other_ids as $id)
            {
                $sim_query = "SELECT * FROM ratings where user_id = ".$id;
                $user_sim_query = "SELECT * FROM ratings where user_id = ".$user_id;

                $result_user_sim = $conn->query($user_sim_query);
                $user_sim_row = $result_user_sim->fetch_assoc();
                $result_other_sim = $conn->query($sim_query);
                $other_sim_row = $result_other_sim->fetch_assoc();

                if ($user_sim_row == 0 && $other_sim_row > 6 && !in_array($id, $recommendation))
                {
                    array_push($recommendation, $other_sim_row[$id]);
                }
            }

            foreach ($)
            $book_query = "SELECT * FROM book";
            $result_book = $conn->query($book_query);
            
            if ($result_book->num_rows > 0)
            {
                echo "<table border = '1'
                <tr>
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Book Author</th>
                </tr>";
                while ($book_row = $result_book->fetch_assoc())
                {
                    echo "<tr>";
                    echo "<td>".$book_row["book_id"]."</td>";
                    echo "<td>".$book_row["title"]."</td>";
                    echo "<td>".$book_row["author"]."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else
            {
                echo "No Recommendations Available<br>";
            }

            $user_query->close();
            $other_query->close();
            $sim_query->close();
            $user_sim_query->close();
            $book_query->close();
            */
            
            $conn->close();
        ?>
    </body>
</html>
