<?php include("topbit.php");

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
$inapp = 1;
$description = "";

$has_errors = "no";

// Code below excutes when the form is submitted...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "You pushed the button";
    
}   // end of button submitted code

?>

    <div class="box main">
        <div class="add-entry">
        <h2>Add An Entry</h2>
        
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            
        <!-- App Name (Required) -->
        <input class="add-field" type="text" name="app_name" value="<?php echo $app_name; ?>" required placeholder="App Name (required) ..."/>
            
        <!-- Subtitle (optional) -->
            
        <!-- URL (Required, must start http://) -->
            
        <!-- Genre dropdown (required) -->
            
        <!-- Developer Name (Required -->
        
        <!-- Age (set to 0 if left blank) -->
            
        <!-- Rating (Number between 0-5, 1 dp) -->
        <div> 
            <input class="add-field" type="number" name="rating" value="<?php echo $rating; ?>" required step = "0.1" min=0 max=5 placeholder="Rating (0-5)"/>
        </div>
        
        <!-- # of ratings (integer more than 0) -->
            
        <!-- Cost (Decimal 2dp, must be more than 0) -->
            
        <!-- In App Purchase radio buttons -->
            
        <!-- Description text area -->
            
        <!-- Submit button -->
        <p>
            <input class="submit advanced-button" type="submit" value="Submit" />
        </p>
            
        </form>
        
        </div> <!-- / add -entry -->
    </div>  <!-- / main -->
    
<?php include("bottombit.php") ?>