<?php
$dbcon = mysqli_connect('us-cdbr-east-02.cleardb.com', 'bb0795b70d8d09', 'e17f19a7', 'heroku_49f65ce59122061') or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
