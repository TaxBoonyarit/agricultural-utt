<?php
include('../config/conectDB.php');
$sql = "SELECT * FROM amphurs WHERE PROVINCE_ID='{$_GET['province_id']}'";
$query = mysqli_query($dbcon, $sql);
$json = array();
while ($result = mysqli_fetch_assoc($query)) {
    array_push($json, $result);
}
echo json_encode($json);
