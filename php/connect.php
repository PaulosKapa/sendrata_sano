<!DOCTYPE html>
<?php
    session_start();
    $name = $_POST["name"];
    $user_password = $_POST["password"];
    if (!$name or !$user_password){
        die("please enter a name and a password");
    }
    //database connection
    $host = 'localhost';
    $dbname = "test";
    $username = "root";
    $password = "";
    $aid;
    $pass;
    // Create connection
    $conn = mysqli_connect(hostname: $host, username: $username, password: $password, database: $dbname);
    // Check connection
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_errno());
    }
    $sql = "SELECT AID from accounts where user_name = '$name'";
    $result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
    while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
      $i = 0;
        foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
          //get every value in an index 
          //$values_arr[$i] = $value;
          //$i++;
            $aid =  $value;
            //echo $value; // I just did not use "htmlspecialchars()" function. 
        }
    }
    $sql = "SELECT user_password from accounts where AID = '$aid'";
    $result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
    while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
      $i = 0;
        foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
          //get every value in an index 
          //$values_arr[$i] = $value;
          //$i++;
            $pass =  $value;
            //echo $value; // I just did not use "htmlspecialchars()" function. 
        }
    }
    if($user_password == $pass){
        $_SESSION['aid'] = $aid;
        echo $_SESSION['aid'];
        header("Location: /sendrata_sano/html/write.html");
        exit();
    }
    else{
        header("Location: /sendrata_sano/index.html");
        exit();
    }

    
?>
