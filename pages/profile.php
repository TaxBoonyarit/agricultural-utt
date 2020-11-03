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

<input hidden type="text" id="district" value="<?php echo $district ?>">
<input hidden type="text" id="amphure" value="<?php echo $amphure ?>">


<body>

    <div class=" col-md-6 offset-md-3 col-lg-8 offset-lg-2" id="box">
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
                        <form action="profile_db.php" id="form" method="POST" enctype="multipart/form-data">
                            <input type="text" hidden name="user_id" value="<?php echo $userId = $result['id']; ?>">
                            <input hidden type="text" name="image" value="<?php echo $result['img'] ?>">

                            <div class="form-group">
                                <div class="col">
                                    <label for="img">รูปภาพโปรไฟล์</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="imageUpload" name="img" accept="image/*" />
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
                                <select name="PROVINCE_CODE" id="PROVINCE_CODE" class="custom-select" required>
                                    <?php
                                    $sql = 'SELECT * FROM `provinces` WHERE PROVINCE_ID = ' . $provinces . '';
                                    $result = mysqli_query($dbcon, $sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $row['PROVINCE_ID'] === $provinces ? $status = "selected" : $status = "";
                                            echo '<option ' . $status . ' value="' . $row['PROVINCE_ID'] . '">จ.' . $row['PROVINCE_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-2">
                                <select name="AMPHUR_CODE" id="AMPHUR_CODE" class="custom-select" required>
                                    <?php
                                    $sql = 'SELECT * FROM `amphurs` WHERE PROVINCE_ID = ' . $provinces . '';
                                    $result = mysqli_query($dbcon, $sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $row['AMPHUR_ID'] === $amphure ? $status = "selected" : $status = "";
                                            echo '<option ' . $status . ' value="' . $row['AMPHUR_ID'] . '">อ.' . $row['AMPHUR_NAME'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-2">
                                <select name="DISTRICT_CODE" id="DISTRICT_CODE" class="custom-select" required>
                                    <option value="">เลือกตำบล</option>
                                </select>
                            </div>
                            <input type="text" hidden name="update_profile" value="update_profile">
                    </div>

                    <div class="col-md-12 text-center mt-2">
                        <button type="submit" class="btn btn-secondary btn-md btn-block sub" id="update_profile"><i class="fas fa-sign-in-alt"></i> อัพเดพข้อมูลส่วนตัว</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
            </div>
        </div>
    </div>
</body>
<?php include('layout/footer.php') ?>


<script type="text/javascript">
    $("#success").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });
    $(document).ready(function() {
        var district = $('#district').val();
        var amphure = $('#amphure').val();
        var amphureObject = $('#AMPHUR_CODE');
        var districtObject = $('#DISTRICT_CODE');

        districtObject.html('<option value="">เลือกตำบล</option>');
        $.get('get_district.php?amphure_id=' + amphure, function(data) {
            var result = JSON.parse(data);
            $.each(result, function(index, item) {
                if (district == item.DISTRICT_ID) {
                    districtObject.append(
                        $('<option></option>').attr('selected', true).val(item.DISTRICT_ID).html('ต.' + item.DISTRICT_NAME)
                    );
                }
                districtObject.append(
                    $('<option></option>').val(item.DISTRICT_ID).html('ต.' + item.DISTRICT_NAME)
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

    });

    $("form#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('.sub').prop("disabled", true);
        $('.sub').html('<i class="fa fa-spinner fa-spin"></i> กำลังโหลด...');
        $.ajax({
            url: 'profile_db.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result === 'success') {
                    window.location.replace('profile.php');
                } else if (result === 'errorNotUploadImag') {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถอัพโหลดรูปภาพ',
                        icon: 'error',
                        confirmButtonText: 'ปิด'
                    })
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });


    function readURL(input) {
        if (input.files && input.files[0]) {
            document.getElementById("update_profile").disabled = false;
            //check type image 
            if (!input.files[0].type.match('image/jpeg') && !input.files[0].type.match('image/png') && !input.files[0].type.match('image/gif') && !input.files[0].type.match('image/jpeg')) {
                document.getElementById("update_profile").disabled = true;
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ขออภัยค่ะ! นามสกุลไฟล์ JPG, JPEG, PNG & GIF เท่านั้น.',
                    icon: 'warning',
                    confirmButtonText: 'ปิด'
                })
            }
            //check size image
            if (input.files[0].size > (1048576 * 3)) {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ภาพเกินขนาน 4 MB',
                    icon: 'warning',
                    confirmButtonText: 'ปิด'
                })
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