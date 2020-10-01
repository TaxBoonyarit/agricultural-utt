<?php
session_start();
include('../../config/conectDB.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$name = isset($_POST['name']) ? mysqli_real_escape_string($dbcon, $_POST['name']) : '';
$type = isset($_POST['data_type']) ? $_POST['data_type'] : '';
$reponse = [];

if ($action == 'register') {
    //check name 
    $check = "SELECT * FROM tb_inoutcome_group WHERE inoutcome_group_name = '$name'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        $sql = "INSERT INTO tb_inoutcome_group (inoutcome_group_name,inoutcome_group_type) VALUES ('$name','$type')";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $reponse = array('status' => 'success', 'messages' => '');
            echo json_encode($reponse);
            exit();
        }
    }
}

if ($action == 'update') {
    //check name 
    $check = "SELECT * FROM tb_inoutcome_group WHERE inoutcome_group_name = '$name' AND NOT inoutcome_group_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        $sql = "UPDATE tb_inoutcome_group SET inoutcome_group_name='$name', inoutcome_group_type='$type' WHERE inoutcome_group_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $reponse = array('status' => 'success', 'messages' => '');
            echo json_encode($reponse);
            exit();
        }
    }
}

if ($action == 'delete') {
    $sql = "DELETE FROM tb_inoutcome_group WHERE inoutcome_group_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $reponse = array('status' => 'success', 'messages' => '');
        echo json_encode($reponse);
        exit();
    }
}
