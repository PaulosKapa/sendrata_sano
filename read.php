<!DOCTYPE html>
<?php
  session_start(); // this NEEDS TO BE AT THE TOP of the page before any output etc
  $SID = $_POST["sid"];
  echo $SID;
  $values_arr = array();
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
  $sql = "SHOW COLUMNS FROM test";
  $result = mysqli_query($conn,$sql);
  for($o=0; $o<$row = mysqli_fetch_array($result); $o++){
      //if the column is the LID skip
      //echo $row['Field'];
      if($row['Field']==='LID'){}
      else{
      $column = $row['Field'];
      }
      if($o>0){
          $repeat[$o-1] = $column;
      }    
  }
  
  $sql = "SELECT * FROM `test` WHERE LID = $_SESSION[id]";
  $result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    $i = 0;
      foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
        //get every value in an index 
        $values_arr[$i] = $value;
        $i++;

          //echo $value; // I just did not use "htmlspecialchars()" function. 
      }
  }

  //make the array into a string
  $values_str = implode($values_arr);
  //turn that string back to an array
  $trimmed = str_split($values_str);
  //for the size of that array
  for($j=0; $j<sizeof($trimmed); $j++){ 
    //if there is " replace it with space
    if($trimmed[$j]=='"'){
        $trimmed[$j]=' ';
    }   
}
//turn that array back to a string
$new_str = implode($trimmed);
?>
<html>
  <head>
    <title></title>
  </head>
  <body>    
    <div id="demo" value="<?php echo "$new_str" ?>"></div>
    <div id="form">
        <form action = "read.php" method="post">
            <label id="sid_label" name ="sid"></label>
            <button id="button_clicker" type="submit" onclick="get_sid(), show_message(); return false">READ</button>
        </form>
    </div>
    <div id="txt"></div>
  </body>
  <script>
    //check fot the sid (replace with the raspberry pi code)
    function get_sid(){
      let text = prompt("SID");
      let lbl = document.getElementById("sid_label");
      let txt = document.createTextNode(text);
      lbl.appendChild(txt)     
    }
    //show the selected row after clicking the button
    function show_message() {
      //get the value of the demo
      let id_param = document.getElementById("demo").getAttribute('value');
      //id of button
      let button_click = document.getElementById("txt");
      //create p element
      let text = document.createElement("p")
      //create text
      text_content = document.createTextNode(id_param);
      //put the text as a child of the p element
      text.appendChild(text_content);
      //put the p element as a child of the butto_click element
      button_click.appendChild(text);
    }
  </script>
</html>