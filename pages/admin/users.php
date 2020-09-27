<?php
include('auth.php');
include('../../config/conectDB.php');

$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];

function thai_date_short($time)
{
    global $dayTH, $monthTH_brev;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    return $thai_date_return;
}
function phone_number_format($number)
{
    // Allow only Digits, remove all other characters.
    $number = preg_replace("/[^\d]/", "", $number);

    // get number length.
    $length = strlen($number);

    // if number = 10
    if ($length == 10) {
        $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
    }

    return $number;
}

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
                        <h3 class="text"><i class="fas fa-users"></i> ข้อมูลผู้ใช้งาน</h3>
                        <hr>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">

                                <table id="users" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th class=" text-center" scope="col"><i class="fas fa-sort-numeric-up"></i> ลำดับ</th>
                                            <th class=" text-center" scope="col"><i class="fas fa-user-circle"></i> ชื่อ - นามสกุล</th>
                                            <th class=" text-center" scope="col"><i class="fas fa-phone-square"></i> เบอร์โทร </th>
                                            <th class=" text-center" scope="col"><i class="fas fa-address-book"></i> ที่อยู่ </th>
                                            <th class=" text-center" scope="col"><i class="fas fa-envelope"></i> อีเมล์ </th>
                                            <th class=" text-center" scope="col"><i class="far fa-clock"></i> วันที่เริ่มใช้งาน</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = "SELECT * FROM tb_users WHERE status='user'";
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
                                                $date = (strtotime($data[12]));

                                        ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td><?php echo $data[2] . ' ' . $data[3] ?></td>
                                                    <td><?php echo $data[8] ? phone_number_format($data[8]) : '-' ?></td>
                                                    <td style="width: 30%">
                                                        <?php
                                                        $sql = "SELECT u.address,d.DISTRICT_NAME,a.AMPHUR_NAME, p.PROVINCE_NAME 
                                                        FROM tb_users u 
                                                        LEFT JOIN districts d ON u.district = d.DISTRICT_ID
                                                        LEFT JOIN amphurs a ON u.amphure = a.AMPHUR_ID
                                                        LEFT JOIN provinces p ON u.provinces = p.PROVINCE_ID
                                                        WHERE u.id = '$data[0]'";
                                                        $query = mysqli_query($dbcon, $sql);
                                                        $address = mysqli_fetch_assoc($query);

                                                        echo $address['address'] ? $address['address'] : ' ', " ",
                                                            $address['DISTRICT_NAME'] ? "ต." . $address['DISTRICT_NAME'] : ' ',
                                                            $address['AMPHUR_NAME'] ? "อ." . $address['AMPHUR_NAME'] : ' ',
                                                            $address['PROVINCE_NAME'] ? "จ." . $address['PROVINCE_NAME'] : ' '
                                                                | '-';
                                                        ?></td>
                                                    <td><?php echo $data[10] ?></td>
                                                    <td class="text-center"><?php echo thai_date_short(strtotime($data[12])) ?></td>
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
                <!-- footer -->

                <?php
                include('layout/footer.php');
                ?>

                <!-- end footer -->
            </div>



        </div>
    </div>


</body>
<script>
    $(document).ready(function() {
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
                    "sFirst": "เิริ่มต้น",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "สุดท้าย"
                }
            }
        });
    });
</script>