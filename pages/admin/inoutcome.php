<?php
include('auth.php');
include('../../config/conectDB.php');

?>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>
        <div class="loading" id='loader'>Loading&#8230;</div>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="far fa-money-bill-alt"></i> หมวดหมู่รายรับ/รายจ่าย</h3>
                        <hr>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-2 mt-3 ml-3"> <button href="#" class="btn btn-rounded btn-success open_modal" data-type="i"><i class="fas fa-plus-circle"></i> เพิ่มหมวดหมู่รายรับ</button></div>
                                <div class="col mt-3"> <button href="#" class="btn btn-rounded btn-danger open_modal" data-type="o"><i class="fas fa-plus-circle"></i> เพิ่มหมวดหมู่รายจ่าย</button></div>
                            </div>
                            <div class="card-body">
                                <table id="inoutcome" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ลำดับ</th>
                                            <th class="text-center">ชื่อรายการ</th>
                                            <th class="text-center">หมวดหมู่</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = "SELECT * FROM tb_inoutcome_group";
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
                                                    <td class="text-center"><?php $data[2] === 'i' ?   $type =  "<span class='badge badge-success'>รายรับ</span>" :  $type = "<span class='badge badge-danger'>รายจ่าย</span>";
                                                                            echo $type ?></td>
                                                    <td class="text-right">
                                                        <a class="edit" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>" data-type="<?php echo $data[2] ?>">
                                                            <button type="button" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> แก้ไข</button>
                                                        </a>
                                                        <a class="delete" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>"> <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> ลบ</button></a>

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

            <!-- delete -->
            <div class="modal fade" id="modal_delete" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล?</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <h3 id='title_delete'></h3>
                        </div>
                        <div class="modal-footer">
                            <a class="cls"> <button type="button" class="btn btn-rounded btn-primary" data-dismiss="modal">ยกเลิก</button></a>
                            <button class="btn btn-rounded btn-danger sub_delete">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- insert modal -->
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="ture">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="title" class="modal-title"> </h4>
                            <a class="cls"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> </a>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="col-form-label">รายการ</label>
                                <input id="name" type="text" name="name" class="form-control" required>
                                <p class="text-danger" id='alert_name'></p>
                            </div>
                            <div id="select">
                                <div class="form-group">
                                    <label for="type">หมวดหมู่</label>
                                    <select class="form-control" id="type">
                                        <option value="i" class="text-success">รายรับ</option>
                                        <option value="o" class="text-danger">รายจ่าย</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="cls"> <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                            <button class="btn btn-rounded btn-primary submit" id="btn"></button>
                        </div>
                    </div>
                </div>
            </div>


            <?php
            include('layout/footer.php');
            ?>

        </div>
    </div>

</body>

<script type="text/javascript">
    $(document).ready(function() {
        var loader = document.getElementById('loader');
        loader.style.display = 'none';
        var data_type = '';
        var action = '';
        var id = '';

        $(".alert").fadeTo(3000, 0).slideUp(500, function() {
            $(this).remove();
        });

        $('#inoutcome').DataTable({
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

        $('.open_modal').click(function() {
            data_type = $(this).attr('data-type');
            if (data_type == 'i') {
                $('#title').html('<i class="fas fa-plus-circle"></i> หมวดหมู่รายรับ')
            } else {
                $('#title').html('<i class="fas fa-plus-circle"></i> หมวดหมู่รายจ่าย')
            }
            action = 'register';
            $('#btn').text('บันทึกข้อมูล');
            $('#select').hide();
            $('#modal').modal('show');
        });


        $('.edit').click(function() {
            id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            data_type = $(this).attr('data-type');
            if (data_type == 'i') {
                $('#title').html('<i class="fas fa-redo-alt"></i> หมวดหมู่รายรับ')
            } else {
                $('#title').html('<i class="fas fa-redo-alt"></i> หมวดหมู่รายจ่าย')
            }
            $('#name').val(name);
            action = 'update';
            $('#btn').text('อัพเดตข้อมูล');
            $('#type').val(data_type).change();
            $('#select').show();
            $('#modal').modal('show');
        });

        $('#type').on('change', function(e) {
            data_type = $(this).val();
        });

        $('.delete').click(function() {
            action = 'delete';
            id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $('#title_delete').text(name);
            $('#modal_delete').modal('show');
        });

        $('.sub_delete').click(function() {
            $('#modal_delete').modal('hide');
            loader.style.display = 'block';
            $.ajax({
                url: 'inoutcome_db.php',
                data: {
                    action: action,
                    id: id
                },
                type: 'post',
                success: function(res) {
                    var result = JSON.parse(res);
                    loader.style.display = 'none';
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'สำเร็จ',
                            text: "ลบข้อมูลสำเร็จ",
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        });
                        window.location.replace('inoutcome.php');
                    }
                }
            });
        })

        $('.submit').click(function(e) {
            var name = $('#name').val();
            if (name.length > 0) {
                $('#modal').modal('hide');
                loader.style.display = 'block';
                $.ajax({
                    url: 'inoutcome_db.php',
                    data: {
                        id: id,
                        action: action,
                        name: name,
                        data_type: data_type
                    },
                    type: 'post',
                    success: function(res) {
                        var result = JSON.parse(res);
                        loader.style.display = 'none';
                        if (result.status === 'success') {
                            Swal.fire({
                                title: 'สำเร็จ',
                                text: "ทำรายการสำเร็จ",
                                icon: 'success',
                                confirmButtonText: 'ปิด'
                            });
                            window.location.replace('inoutcome.php');
                        } else if (result.status === 'error' && result.messages === 'nameDuplicate') {
                            $('#modal').modal('show');
                            $('#alert_name').text('***ชื่อซ้ำกัน****');
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด',
                                text: "ชื่อซ้ำกัน",
                                icon: 'error',
                                confirmButtonText: 'ปิด'
                            });
                        }
                    }
                });
            } else {
                $('#alert_name').text('***กรุณกรอกข้อความ****');
            }
        });

        $('.cls').click(function() {
            id = '';
            $('#name').val('');
            $('#alert_name').text('');
        })

    });
</script>

</html>