
<?php
session_start();
include('../config/conectDB.php');
$errors = array();
if (isset($_POST["submit"])) {
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($dbcon, $_POST['confirmpassword']);
    $firstname = mysqli_real_escape_string($dbcon, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbcon, $_POST['lastname']);
    $tel = mysqli_real_escape_string($dbcon, $_POST['tel']);
    $address = mysqli_real_escape_string($dbcon, $_POST['address']);
    $start_date = date("Y-m-d");
    $provinces_id = $_POST['PROVINCE_CODE'];
    $amphure_id = $_POST['AMPHUR_CODE'];
    $district_id = $_POST['DISTRICT_CODE'];
    if (empty($email)) {
        array_push($errors, 'Email is required');
    }
    if (empty($password)) {
        array_push($errors, 'Password is required');
    }
    if (empty($confirmpassword)) {
        array_push($errors, 'The Two passwords do not match');
    }
    if (empty($firstname)) {
        array_push($errors, 'Firstname is required');
    }
    if (empty($lastname)) {
        array_push($errors, 'Lastname is required');
    }
    if (empty($tel)) {
        array_push($errors, 'Tel is required');
    }
    if (empty($address)) {
        array_push($errors, 'Address is required');
    }

    $user_check_query = "SELECT * FROM tb_users WHERE email ='$email'";
    $query = mysqli_query($dbcon, $user_check_query);
    $result = mysqli_fetch_assoc($query);

    if ($result) {
        if ($result['email'] === $email) {
            echo json_encode("emailDuplicate");
            exit();
        }
    } else {
        if (count($errors) == 0) {
            if (isset($_FILES['img'])) {

                $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
                $images =  $_FILES["img"]["tmp_name"];

                $new_image_name = 'user_' . uniqid() . "." . $ext;
                $image_path = "../images/users/";
                $upload_path = $image_path . $new_image_name;

                //uploading images
                $success = move_uploaded_file($images, $upload_path);
                $img = $new_image_name;
                if (!$success) {
                    echo json_encode("errorUploadImage");
                    exit();
                } else {
                    $hash_password = md5($password);
                    $sql = "INSERT INTO tb_users (email,password,firstname,lastname,tel,address,district,amphure,provinces,start_date,img) 
                                VALUES ('$email','$hash_password','$firstname','$lastname','$tel','$address','$district_id','$amphure_id','$provinces_id','$start_date','$img')";
                    mysqli_query($dbcon, $sql);
                    $_SESSION['success'] = "สมัครสมาชิกสำเร็จ";
                    echo  json_encode("success");
                }
            } else {
                $hash_password = md5($password);
                $sql = "INSERT INTO tb_users (email,password,firstname,lastname,tel,address,district,amphure,provinces,start_date,img) 
                        VALUES ('$email','$hash_password','$firstname','$lastname','$tel','$address','$district_id','$amphure_id','$provinces_id','$start_date','$img')";
                mysqli_query($dbcon, $sql);
                $_SESSION['success'] = "สมัครสมาชิกสำเร็จ";
                echo  json_encode("success");
            }
        } else {
            array_push($errors, "อีเมล์นี้ถูกใช้งานแล้ว");
            $_SESSION['checkemail'] = $email;
        }
    }
}


?>