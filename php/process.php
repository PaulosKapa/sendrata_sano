<!DOCTYPE html>
<?php
session_start();
$j = 0;
$i = 0;
$repeat = array();
$allow_write = false;
//get the cookie from js
$cookie = $_COOKIE['cname'];
//get the sid cookie from js
$sid = $_COOKIE['SID_name'];
//make the cookie into an array with every character to have a specific position
$trimmed = str_split($cookie);
//get the size of that array
$size1 = sizeof($trimmed);
//for that size remove [ and ] from the array
for($i = 0; $i<$size1; $i++){
    if($trimmed[$i]=="[" or $trimmed[$i]== ']'){
        $trimmed[$i]='';
    }
}
//make the trimmed array to a string
$new_cookies = implode($trimmed);
//make it into an array again and seperate in the ,
$array = explode(',', $new_cookies);
//find how many level are in the array
$contains_level = substr_count($new_cookies, "level");
//get the size of the array
$size = sizeof($array);
//database connection
$host = "localhost";
$dbname = "sendrata_sano";
$username = "user";
$password = "1813";
// Create connection
$conn = mysqli_connect($host,$username,$password,$dbname);
// Check connection
if (mysqli_connect_errno()) {
  die("Connection failed: " . mysqli_connect_errno());
}
//check if the sid already exists If it does, dont add a new row to the general table
$sql = "SELECT SID FROM levels WHERE SID = '$sid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
print_r($row);// { // Important line !!! Check summary get row on array ..
    if($row == null){
        $sql = "INSERT INTO general_table (SID, AID) VALUES (?,?)";
        //initialise the statement
        $stmt = mysqli_stmt_init($conn);
        //if cant prepare the statement
        if ( ! mysqli_stmt_prepare($stmt, $sql)) {

            die(mysqli_error($conn));
        }
        //bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $sid, $_SESSION['aid']);
        //if you can execute the statement
        mysqli_stmt_execute($stmt);
        //make sure the sid of the user is the same with the sid of the card so the user can't write to other cards
        $duplicate_aid = $_SESSION['aid'];
        $sql = "SELECT SID FROM general_table WHERE AID = $duplicate_aid";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        echo $sid;
        echo $row['SID'];
        if($row['SID']!=$sid){
            $allow_write = false;
        }
        else{
            $allow_write = true;
        }            
    } 
    elseif($row!=null){
        $sql = "SELECT AID FROM general_table WHERE SID = '$sid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        //only allow the user who initially wrote to tha sid to write this sid 
        if ($row['AID'] != $_SESSION['aid']){
            $allow_write = false;
        }
        else{
            //make sure the sid of the user is the same with the sid of the card so the user can't write to other cards
            $duplicate_aid = $_SESSION['aid'];
            $sql = "SELECT SID FROM general_table WHERE AID = $duplicate_aid";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            echo $sid;
            echo $row['SID'];
            if($row['SID']!=$sid){
                $allow_write = false;
            }
            else{
                $allow_write = true;
            }            
        }        
    }
if($allow_write == true){
//code to see the columns from the table
$sql = "SHOW COLUMNS FROM levels";
$result = mysqli_query($conn,$sql);
for($o=0; $o<$row = mysqli_fetch_array($result); $o++){
    //if the column is the LID or SID skip
    if($row['Field']==='LID' or $row['Field'] == 'SID'){}
    else{
    $column = $row['Field'];
    echo $column;
    }
    if($o>1){
        $repeat[$o-2] = $column;
    }    
}
//to insert the sid into the table
$sql = "INSERT INTO levels (SID) VALUES (?)";
//initialise the statement
$stmt = mysqli_stmt_init($conn);
//if cant prepare the statement
if ( ! mysqli_stmt_prepare($stmt, $sql)) {
 
    die(mysqli_error($conn));
}
//bind parameters
mysqli_stmt_bind_param($stmt, "s", $sid);
//if you can execute the statement, then get the id of the column
if (mysqli_stmt_execute($stmt) === TRUE) {
    $last_id = $conn->insert_id;
}

//for how many levels there are
for($i=0; $i<$contains_level; $i++){
    $new_array = [];
    //find the index of the current level in the array
    $level[$i] = '"'. "level" . strval($i).'"';
    //find the index of the next level in the array 
    $level1[$i] = '"'. "level" . strval($i+1).'"';
    $key = array_search($level[$i], $array);
    $key1 = array_search($level1[$i], $array);
    //check the text on the first levels
    if($i+1<$contains_level){
        for($k=0; $k<$key1-$key; $k++){
            //make an array with the content
            $new_array[$k]=$array[$k+$key];
        }
    }
    //check the text on the last level
    else{
        for($k=0; $k<$size-$key; $k++){
            //make an array with the content
            $new_array[$k]=$array[$key+$k];
        }
    }
    //make a string with that array
    $new_string = implode($new_array);
    //set a new variable equal to the level we want to add
    $pos = $array[$key];
    //make that array to an array
    $trimmed1 = str_split($pos);
    //find the size of that array
    $size2 = sizeof($trimmed1);
    //for that size find if there are any " and delete them
    for($j=0; $j<$size2; $j++){
        if($trimmed1[$j]=='"'){
            $trimmed1[$j]='';
        }   
    }
    //make the array back to a string
    $pos = implode($trimmed1);
    //check if there is already a column with that name. If there isn't execute the next code
    if($pos==$repeat[$i]){}
    else{
        //add a new column
        $call = "ALTER TABLE levels ADD $pos VARCHAR( 255 )";
        $stmt = mysqli_stmt_init($conn);
        if( ! mysqli_stmt_prepare($stmt, $call)){
            die(mysqli_error($conn));
        }
        mysqli_stmt_execute($stmt);
        }
    $sql = "UPDATE levels SET $pos = ? WHERE LID = $last_id";
    //initialise the statement
    $stmt = mysqli_stmt_init($conn);
    //if cant prepare the statement
    if ( ! mysqli_stmt_prepare($stmt, $sql)) {
 
        die(mysqli_error($conn));
    }
    //bind parameters
    mysqli_stmt_bind_param($stmt, "s",
                        $new_string
    );
    //execute statement
    mysqli_stmt_execute($stmt);

    //print if succesfull
    echo "Record saved.";
    header("Location: /sendrata_sano/html/choose.html");
    exit();

}
}
else{ 
   header("Location: /sendrata_sano/html/write.html");
   exit();
    echo "Change account";
}
$_COOKIE = [];
mysqli_close($conn);
//error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
