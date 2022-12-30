<?php

$uname = "root";
$dpass = "";
$host = "localhost";
$db = "cryptography";

$conn = mysqli_connect("$host" , "$uname" , "$dpass" , "$db") or die("DB connection error");
?>