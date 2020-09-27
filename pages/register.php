<?php
session_start();
$email = isset($_SESSION['checkemail']) ? $_SESSION['checkemail']  : '';
include '../pages/layout/header.php';
include('../config/conectDB.php');

?>

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="loading" id='loader'>Loading&#8230;</div>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2" id="box">
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
                        <form id="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col">
                                    <label for="img">รูปภาพโปรไฟล์</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUpload" name="img" accept="image/*" />
                                            <label for="imageUpload"><i class="fas fa-camera mt-2 ml-2"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview" style="background-image: url(../images/users/user_default.png);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="text-center text-warning" for="imageUpload"><i class="fas fa-arrow-up"></i> เพิ่มรูปภาพโปรไฟล์</label>
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
                                <select name="PROVINCE_CODE" id="PROVINCE_CODE" class="custom-select" required>
                                    <?php
                                    $sql = 'SELECT * FROM `provinces` WHERE PROVINCE_ID="41" ';
                                    $result = mysqli_query($dbcon, $sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<option value="' . $row['PROVINCE_ID'] . '">จ.' . $row['PROVINCE_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-2">
                                <select name="AMPHUR_CODE" id="AMPHUR_CODE" class="custom-select" required>
                                    <option value="">เลือกอำเภอ</option>
                                </select>
                            </div>

                            <div class="input-group mb-2">
                                <select name="DISTRICT_CODE" id="DISTRICT_CODE" class="custom-select" required>
                                    <option value="">เลือกตำบล</option>
                                </select>
                            </div>
                    </div>
                    <input hidden name="submit" id="submit" value="Submit" />
                    <div class="col-md-12 text-center ">
                        <button class="btn btn-outline-secondary btn-md btn-block" type="submit" id="register"><i class="fas fa-sign-in-alt"></i> ลงทะเบียน</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center mt-4 mb-3">
            <a class="btn btn-primary  btn-sm back" href="login.php"><i class="fas fa-book-reader"></i> หน้าล็อกอินเพื่อเข้าสู่ระบบ</a>
        </div>
    </div>
</body>


<?php
unset($_SESSION['checkemail']);
?>

<script type="text/javascript">
    var loader = document.getElementById('loader');
    loader.style.display = 'none';
    var provinceObject = $('#PROVINCE_CODE');
    var amphureObject = $('#AMPHUR_CODE');
    var districtObject = $('#DISTRICT_CODE');


    var provinceId = $(provinceObject).val();
    amphureObject.html('<option value="">เลือกอำเภอ</option>');
    districtObject.html('<option value="">เลือกตำบล</option>');

    $.get('get_amphure.php?province_id=' + provinceId, function(data) {
        var result = JSON.parse(data);
        $.each(result, function(index, item) {
            amphureObject.append(
                $('<option></option>').val(item.AMPHUR_ID).html('อ.' + item.AMPHUR_NAME)
            );
        });
    });


    // on change amphure
    amphureObject.on('change', function() {
        var amphureId = $(this).val();
        districtObject.html('<option value="">เลือกตำบล</option>');
        $.get('get_district.php?amphure_id=' + amphureId, function(data) {
            var result = JSON.parse(data);
            $.each(result, function(index, item) {
                districtObject.append(
                    $('<option></option>').val(item.DISTRICT_ID).html('ต.' + item.DISTRICT_NAME)
                );
            });
        });
    });

    $("form#form").submit(function(e) {
        document.getElementById("register").disabled = false;
        if (!document.getElementById('imageUpload').files.length) {
            document.getElementById("register").disabled = true;
            Swal.fire({
                title: 'คำเตือน!',
                text: 'เลือกรูปภาพโปรไฟล์',
                icon: 'warning',
                confirmButtonText: 'ปิด'
            })
        } else {
            e.preventDefault();
            var formData = new FormData(this);
            loader.style.display = 'block';
            $.ajax({
                url: 'register_db.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result === "emailDuplicate") {
                        loader.style.display = 'none';
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: 'อีเมล์นี้ถูกใช้งานแล้ว',
                            icon: 'error',
                            confirmButtonText: 'ปิด'
                        })
                    } else if (result === "errorUploadImage") {
                        loader.style.display = 'none';
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถอัพโหลดรูปภาพ',
                            icon: 'error',
                            confirmButtonText: 'ปิด'
                        })
                    } else if (result === "success") {
                        loader.style.display = 'none';
                        Swal.fire({
                            title: 'สำเร็จ',
                            text: 'สมัครสมาชิกสำเร็จ',
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        })
                        setTimeout(() => {
                            window.location.replace('login.php');
                        }, 2000);
                    }
                },
                cache: false,
                contentType: false,
                processData: false,
            });
        }
        return false;
    });

    function checkImage() {
        document.getElementById("register").disabled = false;
        if (!document.getElementById('imageUpload').files.length) {
            document.getElementById("register").disabled = true;
            Swal.fire({
                title: 'คำเตือน!',
                text: 'เลือกรูปภาพโปรไฟล์',
                icon: 'warning',
                confirmButtonText: 'ปิด'
            })
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
                document.getElementById("register").disabled = true;
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ขออภัยค่ะ! นามสกุลไฟล์ JPG, JPEG, PNG & GIF เท่านั้น.',
                    icon: 'warning',
                    confirmButtonText: 'ปิด'
                })
            }
            //check size image
            if (input.files[0].size > (1048576 * 5)) {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ภาพเกินขนาน 5 MB',
                    icon: 'warning',
                    confirmButtonText: 'ปิด'
                })
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