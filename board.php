<html>
<head><title>Message Board</title></head>
<body>
<?php
session_start();
if (isset($_SESSION['user']))
{
$user=$_SESSION['user'];
echo "<h2>Hello ".$user."</h2>";}
else
{session_start();
session_unset();
session_destroy();
ob_start();
header("location:login.php");
ob_end_flush();
exit();}
?>

<form action="board.php" method="GET">
<p><b>New Message:<b><p><textarea rows="4" cols="50" name="new_message"></textarea>
<p><input type="submit" class="button" name="Logout" value="Logout"/>
<p><input type="submit" class="button" name="NewPost" value="NewPost"/><p>
<?php
try {
$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$stmt = $dbh->prepare('select id,replyto,postedby,fullname,datetime,message
from posts,users
where posts.postedby=users.username order by datetime desc');
$stmt->execute();
echo "<table border='1'>";
echo "<tr><th>".'ID'."</th>
<th>".'ReplyTo'."</th>
<th>".'PostedBy'."</th>
<th>".'FullName'."</th>
<th>".'DateTime'."</th>
<th>".'Message'."</th>
<th>Reply</th></tr>";
while($row=$stmt->fetch()){
echo "<tr><td>".$row['id']."</td>
<td>".$row['replyto']."</td>
<td>".$row['postedby']."</td>
<td>".$row['fullname']."</td>
<td>".$row['datetime']."</td>
<td>".$row['message']."</td>
<td><button value=".$row['id']." name='Reply' type='submit'>Reply</button>
</td></tr>";
}
echo "</table>";
}
catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();}
?>
</form>

<div id="messages">
<?php
function logout(){
session_start();
session_unset();
session_destroy();
ob_start();
header("location:login.php");
ob_end_flush();
exit();}

function newpost(){
$mess=$_GET['new_message'];
$key=uniqid();
try {
$dbh = new PDO("mysql:host=127.0.0.1:3306; dbname=board", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$stmt=$dbh->prepare("insert into posts values(?,null,?,now(),?)");
$stmt->bindValue(1, $key, PDO::PARAM_STR);
$stmt->bindValue(2, $_SESSION['user'], PDO::PARAM_STR);
$stmt->bindValue(3, $mess, PDO::PARAM_STR);
$stmt->execute();
header("location:board.php");}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}}

function replyto(){
$rto=$_GET['Reply'];
$mess=$_GET['new_message'];
$key=uniqid();
try {
$dbh = new PDO("mysql:host=127.0.0.1:3306; dbname=board", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$stmt=$dbh->prepare("insert into posts values(?,?,?,now(),?)");
$stmt->bindValue(1, $key, PDO::PARAM_STR);
$stmt->bindValue(2, $rto, PDO::PARAM_STR);
$stmt->bindValue(3, $_SESSION['user'], PDO::PARAM_STR);
$stmt->bindValue(4, $mess, PDO::PARAM_STR);
$stmt->execute();
header("location:board.php");}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}}

if (isset($_SESSION['user']))
{
$usern=$_SESSION['user'];
if (isset($_GET['Logout'])) {
logout();}
if (isset($_GET['NewPost'])) {
newpost();}
if (isset($_GET['Reply'])){
replyto();}
}
else{
session_start();
session_unset();
session_destroy();
ob_start();
header("location:login.php");
ob_end_flush();
exit();}
?>
</div>
</body>
</html>