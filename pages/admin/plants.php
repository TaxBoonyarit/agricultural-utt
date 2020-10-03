<?php
include('auth.php');
include('../../config/conectDB.php');
$status = isset($_SESSION['error']) ? isset($_SESSION['error']) : 0;

?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>

        <div class="dashboard-wrapper">
            <div class="loading" id='loader'>Loading&#8230;</div>

            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="fab fa-pagelines"></i> พืช</h3>
                        <hr>

                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="col-2 mt-3"> <button href="#" class="btn btn-rounded btn-primary insert" data-toggle="modal" data-target="#modal_data"><i class="fas fa-plus-circle"></i> เพิ่มพืช</button></div>
                            <div class="card-body">
                                <div class="table-responsive-sm">
                                    <table id="users" class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">ชื่อพืช</th>
                                                <th class="text-center">หมวดหมู่</th>
                                                <th class="text-center" style="width: 10%">หน่วย</th>
                                                <th class="text-center" style="width: 20%">รายละเอียด</th>
                                                <th class="text-center">สถานนะ</th>
                                                <th class="text-center">รูปภาพ</th>
                                                <th style="width: 20%"></th>
                                            </tr>
                                        </thead>
                                        <?php
                                        $sql = "SELECT plant_id,plant_name,name,description,status,img,tb_plants_group.plantgroup_id,tb_plants.unit FROM tb_plants 
                                                LEFT JOIN tb_plants_group ON tb_plants.plantgroup_id = tb_plants_group.plantgroup_id";
                                        $result = mysqli_query($dbcon, $sql);
                                        $data_table = [];
                                        if ($result->num_rows > 0) {
                                            $data_table = mysqli_fetch_all($result);
                                        }
                                        ?>
                                        <tbody>
                                            <?php if (!empty($data_table)) {
                                                $i = 0;
                                                foreach ($data_table as $data) {
                                                    $i++;
                                            ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $i; ?></td>
                                                        <td><?php echo $data[1]; ?></td>
                                                        <td><?php echo $data[2]; ?></td>
                                                        <td class="text-center"><?php echo $data[7] ? $data[7] : '-' ?></td>
                                                        <td><?php echo substr($data[3], 0, 500), strlen($data[3]) > 500 ? $des = '......' : $des =  '' ?></td>
                                                        <td class="text-center"><?php echo $data[4] === 'active' ? "<span class='badge badge-success'>ใช้งาน</span>" : "<span class='badge badge-danger'>ระงับการใช้งาน</span>"; ?></td>
                                                        <td class="text-center"><img src="../../images/plants/<?php echo $data[5] ?>" class="rounded mx-auto d-block" loading="lazy" alt="..." style="height:130px;width:auto"></td>
                                                        <td class="text-right">
                                                            <a class="edit" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>" data-plantgroup_id="<?php echo $data[6] ?>" data-description="<?php echo $data[3] ?>" data-status="<?php echo $data[4] ?>" data-img="<?php echo $data[5] ?>" data-unit="<?php echo $data[7] ?>">
                                                                <button type="button" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> แก้ไข</button>
                                                            </a>
                                                            <a class="delete" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>" data-img="<?php echo $data[5] ?>"> <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> ลบ</button></a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="moda_delete" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล?</h3>
                            <button type="button" class="close cls" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <h3 id="delName"></h3>
                        </div>
                        <div class="modal-footer">
                            <a class="cls"> <button type="button" class="btn btn-rounded btn-primary" data-dismiss="modal">ยกเลิก</button></a>
                            <button class="btn btn-rounded btn-danger " id="sub_delete">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="form">
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="title" class="modal-title"><i class="fas fa-plus-circle"></i> เพิ่มข้อมูลพืช</h4>
                                <button type="button" class="close cls" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="col-form-label">ชื่อพืช</label>
                                        <input id="name" type="text" name="name" class="form-control" required>
                                        <div class="text-danger" id="messages">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-group">
                                    <label for="description1" for="description" class="col-form-label">รายละเอียด</label>
                                    <textarea type="area" class="form-control" id="description" name="description" rows="4">-</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label for="unit" class="col-form-label">หน่วย</label>
                                            <input id="unit" type="text" name="unit" class="form-control" required>
                                        </div>
                                        <div class="col">
                                            <label for="status" class="col-form-label">สถานะ</label>
                                            <select name="status" id="status" class="selectpicker show-tick" data-size="8" data-live-search="true" data-width="100%" required>
                                                <option selected value="active">ใช้งาน</option>
                                                <option value="inactive">ระงับการใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input hidden type="text" name="action" id="action" value="">
                                <input hidden type="text" name="id" id="id" value="">
                                <input hidden type="text" name="eimg" id="eimg" value="">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="img" class="col-form-label">รูปภาพ</label>
                                        <input type="file" class="form-control" id="img" name="img">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <img src="" class="img-thumbnail rounded mx-auto d-block " id="show_img" width="250px" height="100%">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a class="cls"> <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                                <button class="btn btn-rounded btn-primary" id="btn" type="submit">บันทึกข้อมูล</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- footer -->
            <?php
            include('layout/footer.php');
            ?>
            <!-- end footer -->
        </div>
    </div>
    <?php
    unset($_SESSION['name']);
    ?>
    <!-- end main wrapper -->
</body>

<script type="text/javascript">
    $(document).ready(function() {
        $('#show_img').attr('src', '../../images/plants/default.jpg');
        var id, name, img;
        var loader = document.getElementById('loader');
        loader.style.display = 'none';

        $(".alert").fadeTo(3000, 0).slideUp(500, function() {
            $(this).remove();
        });

        $("#img").change(function() {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#show_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#users').DataTable({
            "language": {
                "sProcessing": "กำลังดำเนินการ...",
                "sLengthMenu": "แสดง_MENU_ แถว",
                "sZeroRecords": "ไม่พบข้อมูล",
                "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
                "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                "sInfoPostFix": "",
                "sSearch": "ค้นหา:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "เริ่มต้น",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "สุดท้าย"
                }
            }
        });
        $('.insert').click(function() {
            $('#action').val('register');
            $('#title').html('<i class="fas fa-plus-circle"></i> เพิ่มข้อมูลพืช')
            $('#modal').modal('show');
        });

        $("#form").on('submit', (function(e) {
            e.preventDefault();
            var dataForm = new FormData(this);
            $('#modal').modal('hide');
            loader.style.display = 'block';
            $.ajax({
                url: 'plants_db.php',
                data: dataForm,
                type: 'POST',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    loader.style.display = 'none';
                    let result = JSON.parse(res);
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'สำเร็จ',
                            text: "ทำรายการสำเร็จ",
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        });
                        setTimeout(() => {
                            window.location.replace('plants.php');
                        }, 1500)
                    }
                    if (result.status === 'error' && result.messages === 'notUpload') {
                        $('#modal').modal('show');
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: "ไม่สามารถอัพโหลดรูปภาพ",
                            icon: 'error',
                            confirmButtonText: 'ปิด'
                        });
                    }
                    if (result.status === 'error' && result.messages === 'nameDuplicate') {
                        $('#modal').modal('show');
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: "ชื่อซ้ำกัน",
                            icon: 'error',
                            confirmButtonText: 'ปิด'
                        });
                        $('#messages').show();
                        $('#messages').text('**ชื่อซ้ำกัน***');
                    }
                }
            });
        }));


        $('.edit').click(function() {
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            let plantgroup_id = $(this).attr('data-plantgroup_id');
            let description = $(this).attr('data-description');
            let status = $(this).attr('data-status');
            let unit = $(this).attr('data-unit');
            let img = $(this).attr('data-img');
            $('#show_img').attr('src', '../../images/plants/' + img);
            $('#action').val('update');
            $('#title').html('<i class="fas fa-redo-alt"></i> อัพเดตข้อมูลพืช')
            $('#btn').html('อัพเดตข้อมูล')
            $('#id').val(id);
            $('#eimg').val(img);
            $('#name').val(name);
            $('#plantgroup').val(plantgroup_id).change();
            $('#description').text(description);
            $('#unit').val(unit);
            $('#status').val(status).change();
            $('#modal').modal('show');
        });

        $('.delete').click(function() {
            id = $(this).attr('data-id');
            name = $(this).attr('data-name');
            img = $(this).attr('data-img');
            $('#delName').text(name);
            $('#moda_delete').modal('show');
        });


        $('#sub_delete').click(function() {
            $('#moda_delete').modal('hide');
            loader.style.display = 'block';
            $.ajax({
                url: 'plants_db.php',
                data: {
                    id: id,
                    img: img,
                    action: 'delete'
                },
                type: 'POST',
                success: function(res) {
                    loader.style.display = 'none';
                    let result = JSON.parse(res);
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'สำเร็จ',
                            text: "ทำรายการ",
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        });
                        setTimeout(() => {
                            window.location.replace('plants.php');
                        }, 1500)
                    }
                }
            });
        });

        $('.cls').click(function() {
            $('#show_img').attr('src', '../../images/plants/default.jpg');
            $('#plantgroup').val('').change();
            $('#img').val('');
            $('#id').val('');
            $('#name').val('');
            $('#unit').val('');
            $('#description').text('-');
            $('#eimg').val('');
        });
    });
</script>

</html>