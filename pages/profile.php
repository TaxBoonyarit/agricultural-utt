<?php
include('../pages/auth.php');
include '../pages/layout/header.php';
include('../config/conectDB.php');

$email = $_SESSION['email'];

$sql = "SELECT * FROM tb_users WHERE email = '$email'";
$query  = mysqli_query($dbcon, $sql);
$result  = mysqli_fetch_array($query);
$userId = $result['id'];

$district = $result['district'];
$amphure = $result['amphure'];
$provinces  = $result['provinces'];
$checkImg = $result['img'];

?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<body onload="$('#alert').hide();">
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success">
                <i class="fas fa-user-check"></i>
                <?php
                echo  $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5><i class="fas fa-users-cog"></i> จัดการข้อมูลส่วนตัว</h5>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="profile_db.php" method="POST" enctype="multipart/form-data">
                            <input type="text" hidden name="user_id" value="<?php echo $userId = $result['id']; ?>">
                            <input hidden type="text" name="image" value="<?php echo $result['img'] ?>">

                            <div class="form-group">
                                <div class="col">
                                    <label for="img">รูปภาพโปรไฟล์</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUpload" name="img" accept=".png, .jpg, .jpeg, .git" />
                                            <label for="imageUpload"><i class="fas fa-camera mt-2 ml-2"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <?php if ($checkImg) {
                                                $image = substr($result['img'], 0, 5);
                                            }
                                            if ($image === "https") :
                                            ?>
                                                <div id="imagePreview" style="background-image: url(<?php echo $result['img']; ?>);">
                                                </div>

                                            <?php elseif ($image === "user_") : ?>
                                                <div id="imagePreview" style="background-image: url(../images/users/<?php echo $result['img']; ?>);">
                                                </div>
                                            <?php else : ?>
                                                <div id="imagePreview" style="background-image: url(../images/users/user_default.png);">
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- alert error -->
                            <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert">
                                <div class="row">
                                    <i class="fas fa-exclamation-triangle"></i>&nbsp;
                                    <p id="alertPicture"></p>
                                </div>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>


                            <div class="input-group mb-1">
                                <span id='message'></span>
                            </div>
                            <label for="">ข้อมูลส่วนตัว</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-address-book"></i></span>
                                </div>
                                <input type="text" class="form-control" id="firstname" value="<?php echo $result['firstname']; ?>" name="firstname" placeholder="ชื่อ" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-address-book"></i></span>
                                </div>
                                <input type="text" class="form-control" id="lastname" value="<?php echo $result['lastname']; ?>" name="lastname" placeholder="นามสกุล" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                </div>
                                <input type="tel" class="form-control" id="tel" name="tel" value="<?php echo $result['tel']; ?>" placeholder="เบอร์โทร" maxlength="10" size="10" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
                                </div>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $result['address']; ?>" placeholder="ที่อยู่" required>
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
                                            $row['DISTRICT_CODE'] === $district ? $status = "selected" : $status = "";
                                            echo '<option ' . $status . ' value="' . $row['DISTRICT_CODE'] . '" tag="' . $row['AMPHUR_CODE'] . '" data-subtext="อ.' . $row['AMPHUR_NAME'] . ' จ.' . $row['PROVINCE_NAME'] . '" tag="' . $row['AMPHUR_CODE'] . '">' . $row['DISTRICT_NAME'] . '</option>';
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
                                            if ($g <> $row['PROVINCE_CODE']) {
                                                if ($g <> "") {
                                                    echo '</optgroup>';
                                                }
                                                echo '<optgroup label="' . $row['PROVINCE_NAME'] . '">';
                                                $g = $row['PROVINCE_CODE'];
                                            }
                                            $row['AMPHUR_CODE'] === $amphure ? $status = "selected" : $status = "";
                                            echo '<option  ' . $status . ' value="' . $row['AMPHUR_CODE'] . '" data-subtext="จ.' . $row['PROVINCE_NAME'] . '">' . $row['AMPHUR_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group mb-2">
                                <select name="PROVINCE_CODE" id="PROVINCE_CODE" class="selectpicker show-tick" data-size="8" data-live-search="true" title="จังหวัด" data-width="100%" required>
                                    <?php
                                    $sql = 'SELECT * FROM `provinces`
                                                    WHERE `PROVINCE_CODE`="53"';
                                    $result = mysqli_query($dbcon, $sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $row['PROVINCE_CODE'] === $provinces ? $status = "selected" : $status = "";

                                            echo '<option ' . $status . ' value="' . $row['PROVINCE_CODE'] . '">' . $row['PROVINCE_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                    </div>

                    <div class="col-md-12 text-center mt-2">
                        <button type="submit" class="btn btn-outline-secondary btn-md btn-block" id="update_profile" name="update_profile"><i class="fas fa-sign-in-alt"></i> อัพเดพข้อมูลส่วนตัว</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-12 text-center mt-2 mb-5">
            <a href="../index.php"><i class="fas fa-book-reader"></i> หน้าหลัก</a>
        </div>


</body>
<?php include('layout/footer.php') ?>


<script>
    $('#alert').hide();

    $("#success").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            document.getElementById("update_profile").disabled = false;
            //check type image 
            if (!input.files[0].type.match('image/jpeg') && !input.files[0].type.match('image/png') && !input.files[0].type.match('image/gif') && !input.files[0].type.match('image/jpeg')) {
                document.getElementById("alert").style.display = "block";
                document.getElementById('alertPicture').innerHTML = "ขออภัยค่ะ! นามสกุลไฟล์ JPG, JPEG, PNG & GIF เท่านั้น.";
                document.getElementById("update_profile").disabled = true;
            }
            //check size image
            if (input.files[0].size > (1048576 * 3)) {
                document.getElementById("alert").style.display = "block";
                document.getElementById('alertPicture').innerHTML = "ภาพเกินขนาน 4 MB";
                document.getElementById("update_profile").disabled = true;
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