<<<<<<< HEAD
<?php
function OpenCon()
{
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "ATIVOS";
$conn = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die("Connect failed: %s\n". $conn -> error);
return $conn;
}
function CloseCon($conn)
{
$conn -> close();
}
=======
<?php
function OpenCon()
{
$dbhost = "localhost";
$dbuser = "mariok5";
$dbpass = "mariok5";
$dbname = "ativos";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname) or die("Connect failed: %s\n". $conn -> error);
return $conn;
}
function CloseCon($conn)
{
$conn -> close();
}
>>>>>>> 58c0e16c2ee252e2e4c723731537ec71f26fafbb
?>