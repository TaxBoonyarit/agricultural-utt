<?php
include('../pages/auth.php');
include('../config/conectDB.php');

$plants = $_GET['plants_id'];
$sql = "SELECT unit FROM tb_plants WHERE plant_id = '$plants'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);
echo $result['unit'];
