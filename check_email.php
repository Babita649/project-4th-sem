
<?php
/*
$username=$_POST['email'];
$connect=new mysqli('localhost','root','','cybercafe');
if($connect->connect_errno !=0){
    die('Connection Error:' .$connect->connect_error);
}
$sql="Select * from users where email='$email'";
$res=$connect->query($sql);
if($res->num_rows==1){
    echo "<span style='color:red'>Email Already Taken!</span>";
}else{
      echo "<span style='color:green'>Email Available!</span>";
}?>
*/
include "db.php";

$email = $_POST['email'];

$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "exists";
} else {
    echo "ok";
}
?>
