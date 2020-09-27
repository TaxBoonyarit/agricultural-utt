<?php


include('../../config/conectDB.php');
header('Content-Type: application/json');
$keyword = $_POST['keyword'];

$sql = "SELECT * FROM tb_plots WHERE status='1'AND name LIKE '%{$keyword}%'";
$query = mysqli_query($dbcon, $sql);
$resultArray = array();
while ($data = mysqli_fetch_assoc($query)) {
    array_push($resultArray, $data);
}
echo json_encode($resultArray);
