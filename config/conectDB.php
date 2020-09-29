<?php
$server = 'us-cdbr-east-02.cleardb.com';
$username = 'b0f75929adfba3';
$password = 'd4ffe290';
$database = 'heroku_4a36dc84fa25935';
$dbcon = mysqli_connect($server, $username, $password, $database) or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
