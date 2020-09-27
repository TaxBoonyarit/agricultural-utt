<?php
$dbcon = mysqli_connect('127.0.0.1', 'root', '', 'agricultural2') or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
