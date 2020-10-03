<?php
session_start();
include('../../config/conectDB.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$reponse  = [];
if ($action == 'register') {
    $s_d =  $_POST['start_date'];
    $s = str_replace('/', '-', $s_d);
    $start_date = date('Y-m-d', strtotime($s));

    $e_d =  $_POST['end_date'];
    $e = str_replace('/', '-', $e_d);
    $end_date = date('Y-m-d', strtotime($e));

    $plantgroup_id  = $_POST['plantgroup'];
    $title = $_POST['title'];
    $description = $_POST['content'];
    $img = '';

    //check name 
    $check = "SELECT * FROM tb_plants_step WHERE title = '$title' AND plantgroup_id='$plantgroup_id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        if ($_FILES['img']['size']) {
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'step_plants' . uniqid() . "." . $ext;
            $image_path = "../../images/step_plants/";
            $upload_path = $image_path . $new_image_name;
            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $reponse = array('status' => 'error', 'messages' => 'notUpload');
                echo json_encode($reponse);
                exit();
            }
            $img = $new_image_name;
            $sql = "INSERT INTO tb_plants_step (plantgroup_id,title,description,start_date,end_date,img) 
            VALUES ('$plantgroup_id','$title','$description','$start_date','$end_date','$img')";
            $result = mysqli_query($dbcon, $sql);
            if ($result) {
                $reponse = array('status' => 'success', 'messages' => '');
                echo json_encode($reponse);
                exit();
            }
        } else {
            $sql = "INSERT INTO tb_plants_step (plantgroup_id,title,description,start_date,end_date,img) 
            VALUES ('$plantgroup_id','$title','$description','$start_date','$end_date','$img')";
            $result = mysqli_query($dbcon, $sql);
            if ($result) {
                $reponse = array('status' => 'success', 'messages' => '');
                echo json_encode($reponse);
                exit();
            }
        }
    }
}

if ($action == "update") {
    $id = $_POST['id'];
    $img = isset($_POST['eimg']) ? $_POST['eimg'] : '';
    $plantgroup_id  = $_POST['plantgroup'];
    $title = $_POST['title'];
    $description = $_POST['content'];

    $s_d =  $_POST['start_date'];
    $s = str_replace('/', '-', $s_d);
    $start_date = date('Y-m-d', strtotime($s));

    $e_d =  $_POST['end_date'];
    $e = str_replace('/', '-', $e_d);
    $end_date = date('Y-m-d', strtotime($e));

    // check name 
    $check = "SELECT * FROM tb_plants_step WHERE title = '$title' AND NOT plants_step_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'notUpload');
        echo json_encode($reponse);
        exit();
    } else {
        if ($_FILES['img']['size']) {
            @unlink('../../images/step_plants/' . $img);
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'step_plants' . uniqid() . "." . $ext;
            $image_path = "../../images/step_plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $reponse = array('status' => 'success', 'messages' => '');
                echo json_encode($reponse);
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "UPDATE tb_plants_step SET plantgroup_id='$plantgroup_id',title='$title',description='$description',start_date='$start_date',end_date='$end_date',img='$img'  WHERE plants_step_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $reponse = array('status' => 'success', 'messages' => '');
            echo json_encode($reponse);
            exit();
        }
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $img = isset($_POST['img']) ? $_POST['img'] : '';
    if ($img) {
        @unlink('../../images/step_plants/' . $img);
    }
    $sql = "DELETE FROM tb_plants_step WHERE plants_step_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $reponse = array('status' => 'success', 'messages' => '');
        echo json_encode($reponse);
        exit();
    }
}
