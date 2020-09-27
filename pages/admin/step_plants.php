<?php
include('auth.php');
include('../../config/conectDB.php');

$date = date("d") . "/" . date("n") . "/" .  (date("Y") + 543);

?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script src="//cdn.ckeditor.com/4.14.1/full/ckeditor.js"></script>

<!-- datepicker thai -->
<script type="text/javascript" src="../../service/datepicker-thai/js/bootstrap-datepicker.js"></script>
<link href="../../service/datepicker-thai/css/datepicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../service/datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>



<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="fas fa-seedling"></i> ขั้นตอนการปลูกพืช</h3>

                        <hr>
                        <?php if (isset($_SESSION['success'])) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-11 ml-3 mt-3">
                                    <div class="form-group">
                                        <select id="step" class="selectpicker show-tick" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืช" data-width="100%" required>
                                            <?php
                                            $sql = "SELECT * FROM tb_plants_group";
                                            $result = mysqli_query($dbcon, $sql);
                                            if ($result->num_rows > 0) {
                                                while ($row  = mysqli_fetch_array($result)) {
                                                    echo '<option  value="' . $row['plantgroup_id'] . '">' . $row['name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 mt-3 mb-2">
                                <button class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#modal_data"><i class="fas fa-plus-circle"></i> เพิ่มขั้นตอนการปลูกพืช
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="step_plants">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- delete -->
                    <form action="step_plants_db.php" method="post">
                        <div class="modal fade" id="delete" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล?</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <h3 id="delName"></h3>

                                        <input hidden type="text" name="delid" id="delid">
                                        <input hidden type="text" name="delstatus" id="delstatus">
                                    </div>
                                    <div class="modal-footer">
                                        <a class="cls"> <button type="button" class="btn btn-rounded btn-primary" data-dismiss="modal">ยกเลิก</button></a>
                                        <button type="submit" class="btn btn-rounded btn-danger">ตกลง</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>




                    <form action="step_plants_db.php" method="post" enctype="multipart/form-data">
                        <div class="modal fade" id="edit_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-redo-alt"></i> อัพเดตขั้นตอนการปลูกพืช</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- parameter -->
                                    <input hidden type="text" name="id" id="id">
                                    <input hidden type="text" name="update" id="update" value="ture">
                                    <input hidden type="text" name="eimg" id="eimg">


                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="plantgroup" class="col-form-label">หมวดหมู่พืช</label>
                                            <select id="plantgroup" name="plantgroup" class="selectpicker show-tick" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืช" data-width="100%" required>
                                                <?php
                                                $sql = "SELECT * FROM tb_plants_group ";
                                                $result = mysqli_query($dbcon, $sql);
                                                $g = '';
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option   value="' . $row['plantgroup_id'] . '">' . $row['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="title" class="col-form-label">หัวข้อ</label>
                                            <input id="title" type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="start_date">เริ่มต้น</label>
                                                <input data-date-language="th" class="form-control" id="start_date2" name="start_date">
                                            </div>
                                            <div class=" form-group col-md-6">
                                                <label for="end_date">สิ้นสุด</label>
                                                <input data-date-language="th" class="form-control" id="end_date2" name="end_date">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-form-label">รายละเอียด</label>
                                            <textarea type="area" class="form-control" id="description2" name="description" rows="4">-</textarea>
                                        </div>

                                        <div class="form-group">
                                            <div class="col text-center">
                                                <label for="img" class="col-form-label">รูปภาพ</label>
                                                <input type="file" class="form-control" id="img" name="img">
                                            </div>
                                            <div class="col text-center">
                                                <img id="show_img" alt="pic" class="mt-2" width="150px" height="auto" loading="lazy">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <a class="cls"> <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                                            <button type="submit" class="btn btn-rounded btn-primary" id="bth">อัพเดตข้อมูล</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <form action="step_plants_db.php" method="post" enctype="multipart/form-data">
                        <div class="modal fade" id="modal_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="exampleModalLongTitle"><i class="fas fa-plus-circle"></i> ขั้นตอนการปลูกพืช</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- parameter -->
                                    <input hidden type="text" name="register" id="register" value="ture">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="plantgroup" class="col-form-label">หมวดหมู่พืช</label>
                                            <select id="plantgroup" name="plantgroup" class="selectpicker show-tick" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืช" data-width="100%" required>
                                                <?php
                                                $sql = "SELECT * FROM tb_plants_group ";
                                                $result = mysqli_query($dbcon, $sql);
                                                $g = '';
                                                while ($row = mysqli_fetch_array($result)) {
                                                    echo '<option  value="' . $row['plantgroup_id'] . '">' . $row['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="title" class="col-form-label">หัวข้อ</label>
                                            <input id="title" type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="start_date">เริ่มต้น</label>
                                                <input data-date-language="th" class="form-control" id="start_date" name="start_date" value="<?php echo $date ?>">
                                            </div>
                                            <div class=" form-group col-md-6">
                                                <label for="end_date">สิ้นสุด</label>
                                                <input data-date-language="th" class="form-control" id="end_date" name="end_date" value="<?php echo $date ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-form-label">รายละเอียด</label>
                                            <textarea type="area" class="form-control" id="description" name="description" rows="4">-</textarea>


                                            <div class="form-group">
                                                <label for="img" class="col-form-label">รูปภาพ</label>
                                                <input type="file" class="form-control" id="img" name="img">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <a class="cls"> <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                                            <button type="submit" class="btn btn-rounded btn-primary" id="bth">บันทึกข้อมูล</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- footer -->
                <?php
                include('layout/footer.php');
                ?>
                <!-- end footer -->

            </div>
        </div>

        <!-- end main wrapper -->


</body>

<script type="text/javascript">
    $.ajax({
        url: "fetch_all_step_plants.php",
        method: "post",
        success: function(data) {
            $('#step_plants').html(data);
        }
    })

    var d = new Date();
    var toDay = d.getDate() + '/' + (d.getMonth() + 1) + '/' + (d.getFullYear() + 543);

    CKEDITOR.replace('description');
    CKEDITOR.replace('description2');

    $(".alert").fadeTo(3000, 0).slideUp(500, function() {
        $(this).remove();
    });

    $("#step").on('change', function() {
        let id = $(this).val();
        $.ajax({
            url: "fetch.php",
            method: "post",
            data: {
                id: id
            },
            success: function(data) {
                $('#step_plants').html(data);
            }
        })
    });

    $('#start_date').datepicker({
        language: 'th-th',
        format: 'dd/mm/yyyy',
        autoclose: true

    });
    $('#end_date').datepicker({
        language: 'th-th',
        format: 'dd/mm/yyyy',
        autoclose: true

    });

    $(document).on('click', '.edit', function() {
        $.ajax({
            url: "fetch_step_plants.php",
            method: "post",
            data: {
                id: $(this).attr('data-id')
            },
            success: function(data) {
                CKEDITOR.instances.description2.setData(data);
            }
        });
        let id = $(this).attr('data-id');
        let plantgroup_id = $(this).attr('data-plantgroup_id');
        let title = $(this).attr('data-title');
        let start_date = convertDate($(this).attr('data-start_date'));
        let end_date = convertDate($(this).attr('data-end_date'));
        let img = $(this).attr('data-img');
        $('#show_img').attr('src', '../../images/step_plants/' + img);
        $('#start_date2').datepicker({
            date: start_date,
            language: 'th-th',
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('#end_date2').datepicker({
            date: end_date,
            language: 'th-th',
            format: 'dd/mm/yyyy',
            autoclose: true
        });
        $('#id').val(id);
        $('#title').val(title);
        $('#plantgroup').val(plantgroup_id).change();
        $('#description2').html(description | '');
        $('#start_date2').val(start_date);
        $('#end_date2').val(end_date);
        $('#eimg').val(img);
        $('#edit_data').modal('show');
    });

    $(document).on('click', '.del', function() {
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        $('#id').val(id);
        $('#delName').text(name);
        $('#delid').val(id);
        $('#delstatus').val(true);
        $('#delete').modal('show');
    });

    $('.cls').click(function() {
        $('#title').val('');
        $('#description').text('-');
        $('#description2').text('-');
        $('#eimg').val('');
    });

    function convertDate(dateString) {
        var p = dateString.split(/\D/g)
        return [p[2], p[1], p[0]].join("-")
    }
</script>