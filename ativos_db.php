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

?>