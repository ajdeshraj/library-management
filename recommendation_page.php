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
            $servername = "localhost";
            $username = "root";
            $password = "Factoid-Suds-Tavern3";
            $dbname = "library";

            $conn = new mysqli($servername, $username, $password, $dbname);
                
            if($conn->connect_error)
            {
                die("Connection failed: ".$conn->connect_error."<br>");
            }

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
            $conn->close();
        ?>
    </body>
</html>
