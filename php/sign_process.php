<!DOCTYPE html>
<?php
    session_start();
    $allow_make = false;
    $name = $_POST["name"];
    $user_password = $_POST["password"];
    $attribute = filter_input(INPUT_POST, "sub_option", FILTER_VALIDATE_INT);
    if (!$name or !$user_password){
        die("please enter a name and a password");
    }
    //database connection
    $host = 'localhost';
    $dbname = "sendrata_sano";
    $username = "user";
    $password = "1813";
    // Create connection
    $conn = mysqli_connect(hostname: $host, username: $username, password: $password, database: $dbname);
    // Check connection
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_errno());
    }
$sql = "SELECT user_name FROM accounts";
$result = mysqli_query($conn, $sql);
for($o=0; $o<$row = mysqli_fetch_assoc($result); $o++){
    if($name == $row["user_name"]){
        $allow_make = false;
    }
    else{
        $allow_make = true;
    }
}

if($allow_make == true){
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
    $sql = "SELECT AID FROM accounts WHERE user_name = '$name'";
    $result = mysqli_query($conn, $sql);
    for($o=0; $o<$row = mysqli_fetch_assoc($result); $o++){
        $_SESSION['aid'] = $row['AID'];
    }
    header("Location: /sendrata_sano/html/choose.html");
    exit();
}
else{
    echo "Change name";
    header("Location: /sendrata_sano/html/sign_up.html");
    exit();
}
    
?>
