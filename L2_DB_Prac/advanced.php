<?php include("topbit.php");

    $app_name = mysqli_real_escape_string($dbconnect, $_POST['app_name']);
    $developer = mysqli_real_escape_string($dbconnect, $_POST['dev_name']);
    $genre = mysqli_real_escape_string($dbconnect, $_POST['genre']);
    $cost = mysqli_real_escape_string($dbconnect, $_POST['cost']);

    // In App Purchases...
    if (isset($_POST['in_app'])) {
        $in_app = 0;
    }

    else {
        $in_app = 1;        
    }

    // Ratings
    $rating_more_less = mysqli_real_escape_string($dbconnect, $_POST['rate_more_less']);
    $rating = mysqli_real_escape_string($dbconnect, $_POST['rating']);

    if ($rating_more_less == "at least") {
        $rate_op = ">="; // '$' means setting up variable
    }

    elseif($rating_more_less == "at most") {
        $rate_op = "<=";
    }

    else {
        $rate_op = "<=";
        $rate_op = 0;
        
    } //end rating if / elseif / else

    $find_sql = "SELECT * FROM `L2_prac_game_details` 
    JOIN `L2_prac_genre` ON (L2_prac_game_details.GenreID = L2_prac_genre.GenreID)
    JOIN `L2_prac_developer` ON (L2_prac_game_details.DeveloperID = L2_prac_developer.DeveloperID)
    WHERE `Name` LIKE '%$app_name%'
    AND `DevName` LIKE '%$developer%'
    AND `Genre` LIKE '%$genre%'
    AND `Price` <= '$cost'
    AND (`In App` = $in_app OR `In App` = 0)
    AND `User Rating` $rate_op $rating
    
    ";
    $find_query = mysqli_query($dbconnect, $find_sql);
    $find_rs = mysqli_fetch_assoc($find_query);
    $count = mysqli_num_rows($find_query);

?>

        <div class="box main">
            <h2>Advanced Search Results</h2>
            
            <?php 
            include ("results.php");
            ?>
            
        </div> <!-- / main -->
        
    <?php include("bottombit.php") ?>