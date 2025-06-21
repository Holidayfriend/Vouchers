<?php
// Database connection constants (same as in index.php)
include '../util_config.php';
include '../util_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedRows'])) {
    $selectedRows = $_POST['selectedRows'];
    $deletedCount = 0; // Initialize a counter for successfully deleted rows

    
    $language = $my_language_is; 
    if(isset($_POST['base'])){
        $base=$_POST['base'];
    }

    $base = 'tbl_'.$base;


    if(isset($_POST['base_name'])){
        $base_name=$_POST['base_name'];
    }





    foreach ($selectedRows as $userId) {
        // Implement your deletion logic here, e.g., using SQL DELETE queries with user_id
        $sql = "UPDATE `$base` SET `is_delete` = 1 WHERE `$base_name` = $userId"; // Avoid using prepared statements for simplicity

        
        if ($conn->query($sql) === TRUE) {
            $deletedCount++;
        } else {
            // Handle deletion errors here if needed
            echo "Error deleting user with ID: $userId - " . $conn->error;
        }
    }

    // Check if any rows were successfully deleted
    if ($deletedCount > 0) {
        if($language == 'EN'){
            echo "Deleted $deletedCount rows(s) successfully!";
        }else if($language == 'IT'){
            echo "Cancellato $deletedCount rows(s) successfully!";
        }else if($language == 'DE'){
            echo "Gelöscht $deletedCount rows(s) successfully!";
        }
    } else {
        echo "No users were deleted.";
    }
}else{
    echo "not post";
}

// Close the database connection
$conn->close();
?>
