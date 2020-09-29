<?php
$dbcon = mysqli_connect('us-cdbr-east-02.cleardb.com', 'b0f75929adfba3', 'd4ffe290', 'heroku_4a36dc84fa25935') or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
