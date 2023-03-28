<!DOCTYPE html>
<?php
    $name = $_POST["name"];
    $user_password = $_POST["password"];
    $attribute = filter_input(INPUT_POST, "sub_option", FILTER_VALIDATE_INT);
    if (!$name or !$user_password){
        die("please enter a name and a password");
    }
    //database connection
    $host = 'localhost';
    $dbname = "test";
    $username = "root";
    $password = "";
    // Create connection
    $conn = mysqli_connect(hostname: $host, username: $username, password: $password, database: $dbname);
    // Check connection
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_errno());
    }
    //insert into the table
    $sql = "INSERT INTO accounts (user_name, user_password, attribute) VALUES (?,?,?)";
    //initialise the statement
    $stmt = mysqli_stmt_init($conn);
    //if cant prepare the statement
    if ( ! mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_error($conn));
    }
    //bind parameters
    mysqli_stmt_bind_param($stmt, "ssi", $name, $user_password, $attribute);
    //execute statement
    if(mysqli_stmt_execute($stmt) == TRUE){
        //print if succesfull
        echo "Record saved.";
    }

    
?>
