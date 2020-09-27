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

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="fas fa-tree"></i> หมวดหมู่พืช</h3>
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
                            <div class="col-2 mt-3"> <button href="#" class="btn btn-rounded btn-primary" data-toggle="modal" data-target="#modal_data"><i class="fas fa-plus-circle"></i> เพิ่มหมวดหมู่พืช</button></div>
                            <div class="card-body">
                                <table id="users" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ลำดับ</th>
                                            <th class="text-center">ชื่อกลุ่มพืช</th>
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
                                                    <td class="text-right">

                                                        <a class="edit" data-id="<?php echo $data[0] ?>" data-name="<?php echo $data[1] ?>">
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


            <!-- Modal Delete -->
            <form action="group_plants_db.php" method="post">
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
                                <!-- parameter -->
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

            <form action="group_plants_db.php" method="post">
                <div class="modal fade" id="edit_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="title" class="modal-title" id="exampleModalLongTitle"><i class="fas fa-redo-alt"></i> อัพเดตหมวดหมู่พืช</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- parameter -->
                            <input hidden type="text" name="id" id="id">
                            <input hidden type="text" name="update" id="update" value="ture">

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">ชื่อกลุ่มพืช</label>
                                    <input id="name" type="text" name="name" class="form-control" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <a class="cls"> <button id="cls" type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                                <button type="submit" class="btn btn-rounded btn-primary">อัพเดตข้อมูล</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>



            <form action="group_plants_db.php" method="post">
                <div class="modal fade" id="modal_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="title" class="modal-title" id="exampleModalLongTitle"><i class="fas fa-plus-circle"></i> เพิ่มข้อมูลหมวดหมู่พืช</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- parameter -->
                            <input hidden type="text" name="register" id="register" value="ture">

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">ชื่อกลุ่มพืช</label>
                                    <input id="name" type="text" name="name" class="form-control" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <a class="cls"> <button type="button" class="btn btn-rounded btn-danger" data-dismiss="modal">ยกเลิก</button></a>
                                <button type="submit" class="btn btn-rounded btn-primary" id="bth">บันทึกข้อมูล</button>
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
        $('.edit').click(function() {
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            $('#id').val(id);
            $('#name').val(name);
            $('#update').val(true);
            $('#edit_data').modal('show');
        });
        $('.delete').click(function() {
            let id = $(this).attr('data-id');
            let name = $(this).attr('data-name');
            $('#delName').text(name);
            $('#delid').val(id);
            $('#delstatus').val(true);
            $('#delete').modal('show');
        });
        $('.cls').click(function() {
            $('#id').val('');
            $('#name').val('');
        });
    });
</script>

</html>