<?php include("topbit.php");

// Get Genre list from database
$genre_sql="SELECT * FROM `L2_prac_genre`";
$genre_query=mysqli_query($dbconnect, $genre_sql);
$genre_rs=mysqli_fetch_assoc($genre_query);

// Initialise form variables

$app_name = "";
$subtitle = "";
$url = "";
$genreID = "";
$dev_name = "";
$age = "";
$rating = "";
$rate_count = "";
$cost = "";
$in_app = 1;
$description = "Please enter a description";

$has_errors = "no";

// set up error field colours / visibility (no errors at first)
$app_error = $url_error = $dev_error = $decription_error = $genre_error = "no-error";

$app_field = $url_field = $dev_field = $decription_field = $genre_field = "form-ok";

$age_message = $cost_message = "";

// Code below excutes when the form is submitted...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get values from form...
    $app_name = mysqli_real_escape_string($dbconnect, $_POST['app_name']);
    $subtitle = mysqli_real_escape_string($dbconnect, $_POST['subtitle']);
    $url = mysqli_real_escape_string($dbconnect, $_POST['url']);
    
    $genreID = mysqli_real_escape_string($dbconnect, $_POST['genre']);
    
    // if GenreID, is not blank, get genre so that genre box does not lose its value if there are errors 
    if ($genreID != "") {
        $genreitem_sql = "SELECT * FROM `L2_prac_genre` WHERE `GenreID` = $genreID";
        $genreitem_query=mysqli_query($dbconnect, $genreitem_sql);
        $genreitem_rs=mysqli_fetch_assoc($genreitem_query);
        
        $genre = $genreitem_rs['Genre'];
        
    }  // End genreID if
    
    $dev_name = mysqli_real_escape_string($dbconnect, $_POST['dev_name']);
    $age = mysqli_real_escape_string($dbconnect, $_POST['age']);
    $rating = mysqli_real_escape_string($dbconnect, $_POST['rating']);
    $rate_count = mysqli_real_escape_string($dbconnect, $_POST['count']);
    $cost = mysqli_real_escape_string($dbconnect, $_POST['price']);
    
     // In App Purchases...
    if (isset($_POST['in_app'])) {
        $in_app = 0;
    }

    else {
        $in_app = 1;        
    }
    
    $description = mysqli_real_escape_string($dbconnect, $_POST['description']);

    // error checking will go here...
    
    // Check App Name is not blank
    if($app_name == "") {
        $has_errors = "yes";
        $app_error = "error-text";
        $app_field = "form-error";
    }
    
    // Check URL is valid...
    
    // Remove all illegal characters froma url
    $url = filter_var($url, FILTER_SANITIZE_URL);
    
    if (filter_var($url, FILTER_VALIDATE_URL) == false) { 
        $has_errors = "yes";
        $url_error = "error-text";
        $url_field = "form-error";
    }

    // Check Genre is not blank
    if ($genreID == "") {
        $has_errors = "yes";
        $genre_error = "error-text";
        $genre_field = "form-error";
    }
        
    // Check developer name is not blank
    if ($dev_name == "") {
        $has_errors = "yes";
        $dev_error = "error-text";
        $dev_field = "form-error";
    }
        
    // Check age is an integer, it is blank, set it to zero
    if (!ctype_digit($age) || $age < 0) {         /* || means 'OR' */
        $has_errors = "yes";
        $count_error = "error-text";
        $count_field = "form-error";
        $age_message = "Age must be an integer more than zero";
    }
        
    // Check rating is a decimal between 0 and 5
    if (!is_numeric($rating) || $rating < 0 || $rating > 5) {
        $has_errors = "yes";
        $rating_error = "error-text";
        $rating_field = "form-error";
    }
        
    // Check number of ratings is an integer that is more than 0
    if (!ctype_digit($rate_count) || $rate_count < 1) {         /* || means 'OR' */
        $has_errors = "yes";
        $count_error = "error-text";
        $count_field = "form-error";
    }
        
    // Check cost is a number, if it's blank, set it to 0
    if ($cost == "" || $cost == "0") {
        $cost = 0;
        $cost_message = "The price has been set to 0 (ie: free)";
        $cost_error = "defaulted";
    }
    
    // Check that age is a number that is more than 0
    else if (!is_numeric($cost) || $cost < 0) {
        $cost_message = "Please enter a number that is 0 or more";
        $cost_errors = "yes";
        $cost_error = "error-text";
        $cost_field = "form-error";
    }
        
    // Check description is not blank / 'Description required'
        
    if ($description == "" || $description == "Please enter a description") {
        $has_errors = "yes";
        $description_error = "error-text";
        $description_field = "form-error";
        $description = "";
    }
            
        // In App purchases...
    if (isset($_POST['in_app'])) {
        $in_app = 0;
    }
    
    else {
        $in_app = 1;
    }
    
    $description = mysqli_real_escape_string($dbconnect, $_POST['description']);
    
    // error checking will go here...
    
    // if there are no errors...
    if ($has_errors == "no") {
    
    // Go to success page...
    header('Location: add_success.php');
    
    // Get developer ID if it exists...
        $dev_sql ="SELECT * FROM `L2_prac_developer` WHERE `DevName` LIKE '$dev_name'";
        $dev_query=mysqli_query($dbconnect, $dev_sql);
        $dev_rs=mysqli_fetch_assoc($dev_query);
        $dev_count=mysqli_num_rows($dev_query);
    
    // If developer not already in developer table, add them and get the 'new' developerID
    if ($dev_count > 0) {
        $developerID = $dev_rs['DeveloperID'];
    }
        
    else {
        $add_dev_sql = "INSERT INTO `kime69800`.`L2_prac_developer` (`DeveloperID` ,`DevName`)VALUES (NULL , '$dev_name');";
        $add_deve_query = mysqli_query($dbconnect,$add_dev_sql);
        
    // Get developer ID
    $newdev_sql = "SELECT * FROM `L2_prac_developer` WHERE `DevName` LIKE '$dev_name'";
    $newdev_query=mysqli_query($dbconnect, $newdev_sql);
    $newdev_rs=mysqli_fetch_assoc($newdev_query);
        
    $developerID = $newdev_rs['DeveloperID'];
        
    }   // end adding developer to developer table
    
    // Add entry to database
    $addentry_sql = "INSERT INTO `kime69800`.`L2_prac_game_details` (`ID`, `Name`, `Subtitle`, `URL`, `GenreID`, `DeveloperID`, `Age`, `User Rating`, `Rating Count`, `Price`, `In App`, `Description`) 
    VALUES (NULL, '$app_name', '$subtitle', '$url', '$genreID', '$developerID', '$age', '$rating', '$rate_count', '$cost', '$in_app', '$description');";
    $addentre_query=mysqli_query($dbconnect,$addentry_sql);
        
    // Get ID for next page
    $getid_sql = "SELECT * FROM `L2_prac_game_details`WHERE 
    `Name` LIKE '$app_name'
    AND `Subtitle` LIKE '$subtitle'
    AND `URL` LIKE '$url'
    AND `GenreID` = $genreID
    AND `DeveloperID` = $developerID
    AND `Age` = $age
    AND `User Rating` = $rating
    AND `Rating Count` = $rate_count
    AND `Price` = $cost
    AND `In App` = $in_app
    ";
    $getid_query=mysqli_query($dbconnect, $getid_sql);
    $getid_rs=mysqli_fetch_assoc($getid_query);
        
    $ID = $getid_rs['ID'];
    $_SESSION['ID']=$ID;
        
    } // end of 'no errors' if
    
    
}   // end of button submitted code

?>

    <div class="box main">
        <div class="add-entry">
        <h2>Add An Entry</h2>
        
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            
        <!-- App Name (Required) -->
        <div class="<?php echo $app_error; ?>">
            Please fill in the 'App Name' field
        </div>
        <input class="add-field <?php echo $app_field; ?>" type="text" name="app_name" value="<?php echo $app_name; ?>" required placeholder="App Name (required) ..."/>
            
        <!-- Subtitle (optional) -->
        <input class="add-field" type="text" name="subtitle" size="40" value="<?php echo $subtitle; ?>" placeholder="Subtitle (optional) ..."/>
            
        <!-- URL (Required, must start http://) -->
        <div class="<?php echo $url_error; ?>">
            Please provide a valid URL
        </div>
        <input class="add-field" <?php echo $url_field; ?> type="text" name="url" size="40" value="<?php echo $url; ?>" placeholder="URL (required) ..."/>
            
        <!-- Genre dropdown (required) -->
        <div class"<?php echo $genre_error; ?>">
            Please choose a genre
        </div>
        <select class="adv <?php echo $genre_field; ?>" name="genre">
            <!-- first / selected option -->
            
            <?php 
            if($genreID=="") {
                ?>
            <option value="" disabled selected>Genre (Choose something)....</option>
            <?php
            }
            
            else {
                ?>
            <option value="<?php echo $genreID; ?>" selected><?php echo $genre; ?></option>
            <?php
            }
            ?>
            
            <!--- get options from database -->
            <?php
            
            do {
                ?>
            <option value="<?php echo $genre_rs['GenreID']; ?>" ><?php echo $genre_rs['Genre']; ?></option>
            
            <?php
            }  // end genre do loop
            while ($genre_rs=mysqli_fetch_assoc($genre_query))
            ?>
            
        </select>
            
        <!-- Developer Name (Required) -->
        <input class="add-field" <?php echo $dev_name; ?> type="text" name="dev_name" value="<?php echo $dev_name; ?>" size="40" placeholder="Developer Name (required) ..."/>
        
        <!-- Age (set to 0 if left blank) -->
        <input class="add-field" type="text" name="age" value="<?php echo $age; ?>" placeholder="Age (0 for all) ..."/>
            
        <!-- Rating (Number between 0-5, 1 dp) -->
        <div> 
            <input class="add-field" type="number" name="rating" value="<?php echo $rating; ?>" step = "0.1" min=0 max=5 placeholder="Rating (0-5)"/>
        </div>
        
        <!-- # of ratings (integer more than 0) -->
        <input class="add-field" type="text" name="count" value="<?php echo $rate_count; ?>" placeholder="# of Ratings"/>
            
            
        <!-- Cost (Decimal 2dp, must be more than 0) -->
        <div class="<?php echo $cost_error; ?>">
            <?php echo $cost_message; ?>
        </div>
        <input class="add-field" type="text" name="price" value="<?php echo $cost; ?>" placeholder="Cost (number only)"/>
        
        <br /><br />
        <!-- In App Purchase radio buttons -->
        <div>
            <!-- defaults to 'yes; --> 
            <!-- NOTE: value in databse is boolean, so 'no' becomes 0 and 'yes' becomes 1 -->
            
            <!-- No In App Checkbox -->
            <input class="adv-txt" type="checkbox" name="in_app" value="0" <?php if(isset($_POST['in_app'])) echo "checked='checked'"; ?> >No In App Purchase
        </div>
            
        <br />
            
        <!-- Description text area -->
        <div class="<?php echo $decription_error; ?>">
            Please enter a valid description.
        </div>
        <textarea class="add-field <?php echo $description_field?>" name="description" placeholder="Description...." rows="6"><?php echo $description; ?></textarea>
            
        <!-- Submit button -->
        <p>
            <input class="submit advanced-button" type="submit" value="Submit" />
        </p>
            
        </form>
        
        </div> <!-- / add -entry -->
    </div>  <!-- / main -->
    
<?php include("bottombit.php") ?>