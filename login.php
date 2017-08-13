<html>
<head><title>Message Board Login</title></head>
<body>

<h2>Enter Username and Password</h2>
<form action="login.php" method="POST">
<p>User Name:<br><input type="text" name="uname" required><br>
Password:<br><input type="password" name="pass" required><br>
<input type="submit" class="button" name="login" value="login"/>
</form>

<div id="message">

<?php
if (isset($_POST['login'])) {
check();
}
function check(){
try{
$user=$_POST['uname'];
$passw=$_POST['pass'];
$password=md5($passw);
$dbh = new PDO("mysql:host=127.0.0.1:3306; dbname=board", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$stmt=$dbh->prepare("SELECT * from users where username=? and password=?");
$stmt->bindValue(1, $user, PDO::PARAM_STR);
$stmt->bindValue(2, $password, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
if($row) {
session_start();
$_SESSION['user'] = $row[username];

header("location: board.php");
}else {
echo "Your Login Name or Password is invalid";
}}

catch(PDOException $e)
{echo "Connection failed: " . $e->getMessage();
}
}
?>
</div>
</body>
</html>