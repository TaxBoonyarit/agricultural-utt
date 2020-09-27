<?php

//login facebook
require_once('config_facebook.php');
$redirectTo = "http://localhost/agricultural-management/pages/login.facebook.php";
$data = ['email'];
$fullURL = $handler->getLoginUrl($redirectTo, $data);

// session_start();
$email = isset($_SESSION['checkemail']) ? $_SESSION['checkemail']  : '';
include '../pages/layout/header.php';
include('../config/conectDB.php');

?>

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5><i class="fas fa-pencil-alt"></i> ลงทะเบียนเกษตรกร</h5>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="register_db.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col">
                                    <label for="img">รูปภาพโปรไฟล์</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUpload" name="img" accept=".png, .jpg, .jpeg, .git" required />
                                            <label for="imageUpload"><i class="fas fa-camera mt-2 ml-2"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" style="background-image: url(../images/users/user_default.png);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert">
                                <div class="row">
                                    <i class="fas fa-exclamation-triangle"></i>&nbsp;
                                    <p id="alertPicture"></p>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <?php if (isset($_SESSION['error'])) : ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php
                                    echo  $_SESSION['error'];
                                    unset($_SESSION['error']);
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif ?>

                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" value="<?php echo $email ? $email : ''  ?>" id="email" name="email" placeholder="อีเมล์ใช้งาน" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน (8-10 ตัวอักษร)" minlength="8" maxlength="10" size="10" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="ยืนยันรหัสผ่าน  ( 8-10 ตัวอักษร)" onchange="checkPassword()" minlength="8" maxlength="10" size="10" required>
                            </div>
                            <div class="input-group mb-1">
                                <span id='message'></span>
                            </div>
                            <label for="">ข้อมูลส่วนตัว</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-address-book"></i></span>
                                </div>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="ชื่อ" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-address-book"></i></span>
                                </div>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="นามสกุล" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                </div>
                                <input type="tel" class="form-control" id="tel" name="tel" placeholder="123-45-678" maxlength="10" size="10" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
                                </div>
                                <input type="text" class="form-control" id="address" name="address" placeholder="ที่อยู่" required>
                            </div>

                            <div class="input-group mb-2">
                                <select name="DISTRICT_CODE" id="DISTRICT_CODE" class="selectpicker show-tick" data-size="8" data-live-search="true" title="ตำบล" data-width="100%" required>
                                    <?php
                                    $sql = 'SELECT * FROM `districts` 
                                                LEFT join  `amphurs` ON `districts`.`AMPHUR_ID`=`amphurs`.`AMPHUR_ID`
                                                LEFT join  `provinces` ON `districts`.`PROVINCE_ID`=`provinces`.`PROVINCE_ID`
                                                WHERE `PROVINCE_CODE`="53"
                                                ORDER BY `AMPHUR_CODE`
                                                ';
                                    $result = mysqli_query($dbcon, $sql);

                                    if ($result->num_rows > 0) {
                                        $g = "";
                                        while ($row = mysqli_fetch_array($result)) {
                                            if ($g <> $row['AMPHUR_CODE']) {
                                                if ($g <> "") {
                                                    echo '</optgroup>';
                                                }
                                                echo '<optgroup label="' . $row['AMPHUR_NAME'] . '">';
                                                $g = $row['AMPHUR_CODE'];
                                            }
                                            echo '<option value="' . $row['DISTRICT_CODE'] . '" tag="' . $row['AMPHUR_CODE'] . '" data-subtext="อ.' . $row['AMPHUR_NAME'] . ' จ.' . $row['PROVINCE_NAME'] . '" tag="' . $row['AMPHUR_CODE'] . '">' . $row['DISTRICT_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-2">

                                <select name="AMPHUR_CODE" id="AMPHUR_CODE" class="selectpicker show-tick" data-size="8" data-live-search="true" title="อำเภอ" data-width="100%" required>
                                    <?php
                                    $sql = 'SELECT * FROM `amphurs` 
                                            LEFT join  `provinces` ON `amphurs`.`PROVINCE_ID`=`provinces`.`PROVINCE_ID`
                                             WHERE `PROVINCE_CODE`="53"
                                                ';
                                    $result = mysqli_query($dbcon, $sql);

                                    if ($result->num_rows > 0) {
                                        $g = "";
                                        while ($row = mysqli_fetch_array($result)) {
                                            print_r($row);
                                            if ($g <> $row['PROVINCE_CODE']) {
                                                if ($g <> "") {
                                                    echo '</optgroup>';
                                                }
                                                echo '<optgroup label="' . $row['PROVINCE_NAME'] . '">';
                                                $g = $row['PROVINCE_CODE'];
                                            }
                                            echo '<option value="' . $row['AMPHUR_CODE'] . '" data-subtext="จ.' . $row['PROVINCE_NAME'] . '">' . $row['AMPHUR_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-2">
                                <select name="PROVINCE_CODE" id="PROVINCE_CODE" class="selectpicker show-tick" data-size="10" data-width="100%" required>
                                    <?php
                                    $sql = 'SELECT * FROM `provinces`
                                                    WHERE `PROVINCE_CODE`="53"';
                                    $result = mysqli_query($dbcon, $sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<option value="' . $row['PROVINCE_CODE'] . '">' . $row['PROVINCE_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                    </div>

                    <div class="col-md-12 text-center ">
                        <button type="submit" class="btn btn-outline-secondary btn-md btn-block" onclick="checkImage()" id="register" name="reg_user"><i class="fas fa-sign-in-alt"></i> ลงทะเบียน</button>
                    </div>
                    </form>

                </div>

                <hr>
                <label for="regis">ลงทะเบียนด้วย</label>
                <div class="col-md-12 mb-2" id="regis">
                    <div class="row">
                        <div class="col">
                            <button type="button" onclick="window.location ='<?php echo $fullURL; ?>'" class="btn btn-primary  btn-block "><i class="fab fa-facebook-f"></i> | Facebook</button> <br>
                        </div>
                        <div class="col">
                            <button id="gp-login-btn" class="btn btn-danger btn-block"><i class="fab fa-google-plus-g"></i> | Google </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center mt-4 mb-5">
            <a class="btn btn-primary  btn-sm back" href="login.php"><i class="fas fa-book-reader"></i> หน้าล็อกอินเพื่อเข้าสู่ระบบ</a>
        </div>
    </div>
</body>
<?php include('layout/footer.php') ?>


<?php
unset($_SESSION['checkemail']);
?>


<script type="text/javascript">
    function bindGpLoginBtn() {
        gapi.load('auth2', function() {
            auth2 = gapi.auth2.init({
                client_id: '1039150249203-qnbbpa350r001iu844pe0voqe7r5pulj.apps.googleusercontent.com',
                scope: 'profile email'
            });
            attachSignin(document.getElementById('gp-login-btn'));
        });
    }

    function attachSignin(element) {
        auth2.attachClickHandler(element, {},
            function(googleUser) {
                // Success
                getCurrentGpUserInfo(googleUser);
            },
            function(error) {
                // Error
                console.log(JSON.stringify(error, undefined, 2));
            }
        );
    }

    function getCurrentGpUserInfo(userInfo) {
        var result = '';

        // Useful data for your client-side scripts:
        var profile = userInfo.getBasicProfile();

        window.location = `login_google.php?id=${profile.getId()}&&firstname=${profile.getGivenName()}&&lastname=${profile.getFamilyName()}&&email=${profile.getEmail()}&&image=${profile.getImageUrl()}`;

    }

    document.getElementById("alert").style.display = "none";

    function checkImage() {
        document.getElementById("register").disabled = false;
        if (!document.getElementById('imageUpload').files.length) {
            document.getElementById("alert").style.display = "block";
            document.getElementById('alertPicture').innerHTML = "เลือกรูปภาพโปรไฟล์.";
            document.getElementById("register").disabled = true;
        }
    }

    function checkPassword() {
        if (document.getElementById('password').value ===
            document.getElementById('confirmpassword').value) {
            document.getElementById("message").innerHTML = null;
            document.getElementById("register").disabled = false;

        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = '***รหัสไม่ตรงกัน***';
            document.getElementById("register").disabled = true;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            document.getElementById("register").disabled = false;

            //check type image 
            if (!input.files[0].type.match('image/jpeg') && !input.files[0].type.match('image/png') && !input.files[0].type.match('image/gif') && !input.files[0].type.match('image/jpeg')) {
                document.getElementById("alert").style.display = "block";
                document.getElementById('alertPicture').innerHTML = "ขออภัยค่ะ! นามสกุลไฟล์ JPG, JPEG, PNG & GIF เท่านั้น.";
                document.getElementById("register").disabled = true;
            }

            //check size image
            if (input.files[0].size > (1048576 * 3)) {
                document.getElementById("alert").style.display = "block";
                document.getElementById('alertPicture').innerHTML = "ภาพเกินขนาน 4 MB";
                document.getElementById("register").disabled = true;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>