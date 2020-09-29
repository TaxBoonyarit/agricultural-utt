<?php
$dbcon = mysqli_connect('us-cdbr-east-02.cleardb.com', 'bfb0e826f1c656', '2d8ec7a0', 'heroku_bd4ae28e630dc6d') or die('disconnect MySQL' . mysqli_connect_error());
mysqli_set_charset($dbcon, 'utf8');
