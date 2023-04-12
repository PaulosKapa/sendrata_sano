<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <script src="https://cdn.jsdelivr.net/pyodide/v0.23.0/full/pyodide.js"></script>
  </head>
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  //Form processing code here
  $SID = $_POST["sid"];
  $name = $_SESSION["aid"];
  $values_arr = array();
  $attribute;
  $level_reach;
  $new_trimmed_str = "";
  //database connection
  $host = 'localhost';
  $dbname = "sendrata_sano";
  $username = "root";
  $password = "";
  // Create connection
  $conn = mysqli_connect(hostname: $host, username: $username, password: $password, database: $dbname);
  // Check connection
  if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_errno());
  }
  //this will execute only if the user has an account
  if($name != null){
  $sql = "SELECT attribute FROM `accounts` WHERE AID = $name";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    $i = 0;
      foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
        //get every value in an index 
        $attribute = $value;
        $i++;
      }
  }
}
  //this will execute if the user doesn't have an account
  if($name == null){
    $attribute = 1;
  }
  $sql = "SHOW COLUMNS FROM levels";
  $result = mysqli_query($conn,$sql);
  for($o=0; $o<$row = mysqli_fetch_array($result); $o++){
      //if the column is the LID skip
      //echo $row['Field'];
      if($row['Field']==='LID' or $row['Field'] === 'SID'){}
      else{
            $column = $row['Field'];
      }
      if($o>1){
          $repeat[$o-2] = $column;
      }
      
  }

 
  $sql = "SELECT * FROM `levels` WHERE SID = $SID";
  $result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
    $i = 0;
      foreach ($row as $field => $value) { // I you want you can right this line like this: foreach($row as $value) {
        //get every value in an index 
        if($attribute == 1){
          if(str_contains($value, 'level0')){
          $values_arr[$i] = $value;
        }
      }
      else if(str_contains($value, 'level')){
        $values_arr[$i] = $value;
      }
        $i++;
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
//remove "level" from string
for ($s = 0; $s<sizeof($values_arr); $s++){
  $trimmed_array = [];
  //get the level of the repetition
  $level_str = "level".$s;
  //for the first repetition check if there is level 0 and replace it with space
  if(str_contains($new_str, $level_str) && $s == 0){
    $trimmed_array[$s] = str_replace($level_str, "", $new_str);
  }
  //for the rest repetitions check if there is level[$s] and replace it with a breakline
  elseif(str_contains($new_trimmed_str, $level_str) && $s > 0){
    $trimmed_array[$s] = str_replace($level_str, "<br/>", $new_trimmed_str);
  }
  //make the array to a string
  $new_trimmed_str = $trimmed_array[$s]; 
}
//print the outcome
echo '<div id = "demo">';
echo '<p>';
echo $new_trimmed_str;
echo '</p>';
echo '</div>';
}
?>

  <body>    
    <div id="form">
        <form action = "/sendrata_sano/php/read.php" method="post">
            <input id="sid_label" name="sid" readonly="readonly">
            <button id="button_clicker" type="submit" onclick="get_sid()">READ</button>
        </form>
    </div>
    <div id="back">
      <a href="/sendrata_sano/html/choose.html"><button>BACK</button></a>
    </div>
  </body>
  <script>
    let data_id;
    //get the sid from a json file and then stringify it
    async function raspi_sid(){
     const res = await fetch("../file.json")
     data_id = await res.json();
     let data_id_str = JSON.stringify(data_id)
     let data_id_new_str = data_id_str.replace('{"id":', "")
      data_id = data_id_new_str.replace('}', "")
    }
    raspi_sid()
    //.then(Response => Response.json())
    //.then(data => data_id = data.id)
    //.then(()=>{console.log(data_id)});
    //check fot the sid (replace with the raspberry pi code)
    function get_sid(){

      //get sid with the keyboard
    //  let text = prompt("SID");
         let lbl = document.getElementById("sid_label");
//      let txt = document.createTextNode(text);

      lbl.setAttribute("value", "'" + data_id + "'");     
    }
    //show the selected row after clicking the button
    // function show_message() {
    //   //get the value of the demo
    //   let id_param = document.getElementById("demo").getAttribute('value');
    //   //id of button
    //   let button_click = document.getElementById("txt");
    //   //create p element
    //   let text = document.createElement("p")
    //   //create text
    //   text_content = document.createTextNode(id_param);
    //   //put the text as a child of the p element
    //   text.appendChild(text_content);
    //   //put the p element as a child of the butto_click element
    //   button_click.appendChild(text);
    // }
  </script>
</html>