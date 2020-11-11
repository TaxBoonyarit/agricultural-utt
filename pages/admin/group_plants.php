<?php
include('auth.php');
include('../../config/conectDB.php');
$status = isset($_SESSION['error']) ? isset($_SESSION['error']) : 0;
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
                        <h3 class="text"><i class="fas fa-tree"></i> หมวดหมู่พืช</h3>
                        <hr>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card" id="data">
                            <div class="col-2 mt-3"> <button id="insert" class="btn btn-rounded btn-primary"><i class="fas fa-plus-circle"></i> เพิ่มหมวดหมู่พืช</button></div>
                            <div class="card-body">
                                <table id="users" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ลำดับ</th>
                                            <th class="text-center">หมวดหมู่พืช</th>
                                            <th class="text-center">ไอคอน</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = "SELECT * FROM tb_plants_group";
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
                                                    <td class="text-center" style="width: 40%;">
                                                        <img src="../../images/plants/<?php echo $data[2] ?>" alt="" width="70px" loading="lazy" height="auto">
                                                    </td>
                                                    <td class="text-right">

                                                        <a class="edit" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>" data-icon="<?php echo $data[2] ?>">
                                                            <button type="button" class="btn btn-sm btn-warning "><i class="fas fa-edit"></i> </button>
                                                        </a>
                                                        <a class="delete" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>" data-icon="<?php echo $data[2] ?>"> <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> </button></a>

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

            <!-- Modal Delete -->
            <div class="modal fade" id="delete" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล?</h3>
                            <button type="button" class="close reset" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <h3 id="delName"></h3>
                        </div>
                        <div class="modal-footer">
                            <a class="cls"> <button type="button" class="btn btn-rounded btn-primary" data-dismiss="modal">ยกเลิก</button></a>
                            <button type="submit" class="btn btn-rounded btn-danger" id="btn_delete">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>

            <form action="group_plants_db.php" method="post" enctype="multipart/form-data">
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="title" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="title"></h5>
                                <button type="button" class="close reset" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">ชื่อหมวดหมู่พืช :</label>
                                    <input type="text" class="form-control" id="name" required>
                                    <div class="text-danger" id="messages">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">ไอคอน</label>
                                    <strong>เลือกรูปภาพ :</strong>
                                    <input type="file" id="image">
                                    <div class="text-danger" id="messages2">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col text-center">
                                        <div id="upload-demo"></div>
                                    </div>
                                    <div class="col text-center" id="per">
                                        <img id="perview" loading="lazy">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-rounded btn-danger reset" data-dismiss="modal">ยกเลิก</button>
                                <button type="button" class="btn  btn-rounded btn-primary insert_data">บันทึกข้อมูล</button>
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


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

<script type="text/javascript">
    var loader = document.getElementById('loader');
    loader.style.display = 'none';

    $(document).ready(function() {
        var icon, name, action, reader;
        var id = '';
        $(".alert").fadeTo(3000, 0).slideUp(500, function() {
            $(this).remove();
        });

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

        $('#insert').click(function() {
            action = 'insert';
            $('#title').html('<i class="fas fa-plus-circle"></i> เพิ่มหมวดหมู่พืช');
            $('#per').hide();
            $('#modal').modal('show');
        });

        var resize = $('#upload-demo').croppie({
            enableExif: true,
            enableOrientation: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $('#image').on('change', function() {
            reader = new FileReader();
            reader.onload = function(e) {
                resize.croppie('bind', {
                    url: e.target.result
                }).then(function() {});
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.delete').on('click', function(e) {
            $('#delete').modal('show');
            let name = $(this).attr('data-name');
            $('#delName').html(name);
            id = $(this).attr('data-id');
            icon = $(this).attr('data-icon');
        });

        $('.edit').on('click', function(e) {
            action = 'update';
            $('#title').html('<i class="fas fa-redo-alt"></i> อัพเดตหมวดหมู่พืช');
            $('#per').show();
            id = $(this).attr('data-id')
            name = $(this).attr('data-name');
            icon = $(this).attr('data-icon');
            $('#name').val(name);
            $("#perview").attr("src", "../../images/plants/" + icon);
            $('#modal').modal('show');
        });

        $('.reset').on('click', function() {
            id = '';
            action = '';
            $('#upload-demo').attr('src', '');
            $('#name').val('');
            $('#image').val('');
            $('#messages').hide();
            $('#messages2').hide();
        });

        $('#btn_delete').on('click', function() {
            $('#delete').modal('hide');
            loader.style.display = 'block';
            $.ajax({
                url: "group_plants_db.php",
                type: "POST",
                data: {
                    action: 'delete',
                    id: id,
                    icon: icon
                },
                success: function(resposne) {
                    let result = JSON.parse(resposne)
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'สำเร็จ',
                            text: "ลบข้อมูลสำเร็จ",
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        });
                        loader.style.display = 'none';
                        setTimeout(() => {
                            window.location.replace('group_plants.php');
                        }, 1500)
                    }
                }
            });
        });


        $('.insert_data').on('click', function(ev) {
            let name = $('#name').val();
            let file = $('#image').val();
            if (name.length === 0) {
                $('#messages').show();
                $('#messages').text('**โปรดกรอกข้อมูล***');
            } else if (!file && action == 'insert') {
                $('#messages2').show();
                $('#messages2').text('**โปรดเลือกรูปภาพ***');
            } else {
                resize.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function(img) {
                    var pic = img;
                    !file ? pic = '' : pic;
                    $('#modal').modal('hide');
                    loader.style.display = 'block';
                    $.ajax({
                        url: "group_plants_db.php",
                        type: "POST",
                        data: {
                            id: id,
                            action: action,
                            name: name,
                            icon: icon,
                            newIcon: pic
                        },
                        success: function(data) {
                            loader.style.display = 'none';
                            var result = JSON.parse(data);
                            if (result.status === 'success') {
                                Swal.fire({
                                    title: 'สำเร็จ',
                                    text: "บันทึกข้อมูลสำเร็จ",
                                    icon: 'success',
                                    confirmButtonText: 'ปิด'
                                });
                                setTimeout(() => {
                                    window.location.replace('group_plants.php');

                                }, 1500)
                            } else if (result.status === 'error' && result.messages === 'notUpload') {
                                $('#modal').modal('show');
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: "ไม่สามารถอัพโหลดรูปภาพ",
                                    icon: 'error',
                                    confirmButtonText: 'ปิด'
                                });
                            } else if (result.status === 'error' && result.messages === 'nameDuplicate') {
                                $('#modal').modal('show');
                                Swal.fire({
                                    title: 'เกิดข้อผิดพลาด',
                                    text: "ชื่อพืชซ้ำกัน",
                                    icon: 'error',
                                    confirmButtonText: 'ปิด'
                                });
                                $('#messages').show();
                                $('#messages').text('**ชื่อซ้ำกัน***');
                            }
                        }
                    });
                });
            }
        });
    });
</script>

</html>