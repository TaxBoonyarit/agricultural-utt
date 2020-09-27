<?php
session_start();
include('../../config/conectDB.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$sql = "SELECT  * FROM tb_plants_step WHERE plants_step_id='$id'";
$query = mysqli_query($dbcon, $sql);
$resutl = mysqli_fetch_array($query);

echo $resutl['description'];
