<?php
session_start();
include('../../config/conectDB.php');


$action = isset($_POST['action']) ? $_POST['action'] : '';

$plantgroup_id = isset($_POST['plantgroup']) ? $_POST['plantgroup'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$unit = isset($_POST['unit']) ? $_POST['unit'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$img = isset($_POST['eimg']) ? $_POST['eimg'] : '';

$reponse = [];
if ($action == 'register') {
    //check name 
    $check = "SELECT * FROM tb_plants WHERE plant_name = '$name'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        if ($_FILES['img']['size']) {
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'plants' . uniqid() . "." . $ext;
            $image_path = "../../images/plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $reponse = array('status' => 'error', 'messages' => 'notUpload');
                echo json_encode($reponse);
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "INSERT INTO tb_plants (plant_name,plantgroup_id,description,status,img,unit) VALUES ('$name','$plantgroup_id','$description','$status','$img','$unit')";
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
    $check = "SELECT * FROM tb_plants WHERE plant_name = '$name' AND NOT plant_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        if ($_FILES['img']['size']) {
            @unlink('../../images/plants/' . $img);
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'plants' . uniqid() . "." . $ext;
            $image_path = "../../images/plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $reponse = array('status' => 'error', 'messages' => 'notUpload');
                echo json_encode($reponse);
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "UPDATE tb_plants SET plant_name='$name',plantgroup_id='$plantgroup_id',
                description='$description',status='$status',img='$img',unit='$unit'
                WHERE plant_id='$id'";

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
    $img = $_POST['img'];
    if ($img) {
        @unlink('../../images/plants/' . $img);
    }
    $sql = "DELETE FROM tb_plants WHERE plant_id = '$id'";

    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $reponse = array('status' => 'success', 'messages' => '');
        echo json_encode($reponse);
        exit();
    }
}
