<?php
require_once('config_facebook.php');
include('../config/conectDB.php');
print_r($_SESSION);
try {
    $accessToken = $handler->getAccessToken();
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    echo "Response Exception: " . $e->getMessage();
    exit();
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
    echo "SDK Exception: " . $e->getMessage();
    $_SESSION['error'] = "อีเมล์นี้ได้ลงทะเบียนของระบบแล้ว " . $email;
    header('location: login.php');
    exit();
}

if (!$accessToken) {
    header('location: login.php');
    exit();
}
$oAuth2Client = $FBOject->getOAuth2Client();
if (!$accessToken->isLongLived())
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
$response = $FBOject->get("/me?fields=id,first_name,last_name,email,picture.type(large)", $accessToken);
$userData = $response->getGraphNode()->asArray();
$email = $userData['email'];

$_SESSION['access_token'] = (string) $accessToken;
print_r($_SESSION);
if ($accessToken) {
    //data for facebook
    $id_face = $userData['id'];
    $first_name = $userData['first_name'];
    $last_name =  $userData['last_name'];
    $picture = $userData['picture']['url'];

    //check email and id facebook
    $sql = "SELECT * FROM tb_users WHERE email = '$email'";
    $query = mysqli_query($dbcon, $sql);
    $result = mysqli_fetch_assoc($query);

    if ($result['email'] === $email && !isset($result['id_F_L_G'])) {
        $_SESSION['error'] = "อีเมล์นี้ได้ลงทะเบียนของระบบแล้ว " . $email;
        header('location: login.php');
        exit();
    } else   if ($email === $result['email'] && $id_face === $result['id_F_L_G']) {
        $_SESSION['success'] = "ล็อกอินด้วย Facebook สำเร็จ";
        $_SESSION['email'] = $email;
        header('location: index.php');
        exit();
    } else {
        $start_date = date("Y-m-d");
        $sql_query = "INSERT INTO tb_users (id_F_L_G,firstname,lastname,email,img,start_date)
                      VALUES ('$id_face','$first_name',' $last_name','$email','$picture','$start_date')";
        mysqli_query($dbcon, $sql_query);
        $_SESSION['email'] = $email;
        $_SESSION['success'] = "ลงทะเบียนด้วย Facebook สำเร็จ";
        header('location: index.php');
        exit();
    }
}
