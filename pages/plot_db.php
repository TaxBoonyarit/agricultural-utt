<?php

include('../config/conectDB.php');
$plot_id =  $_REQUEST['plot_id'];

$sql  = "UPDATE tb_plots SET status=0 WHERE plot_id='$plot_id'";
$query = mysqli_query($dbcon, $sql);
$_SESSION['success'] = "ลบข้อมูลสำเร็จ";
header('location: plot.php');
