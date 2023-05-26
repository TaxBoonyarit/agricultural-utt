<?php
$server = 'db';
$username = $_ENV["MYSQL_USER"];
$password = $_ENV["MYSQL_PASSWORD"];
$database = $_ENV["MYSQL_DATABASE"];
$dbcon = mysqli_connect($server, $username, $password, $database) or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
