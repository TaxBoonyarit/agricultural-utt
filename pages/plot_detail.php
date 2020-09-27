<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plot_id = $_REQUEST['plot_id'];

$sql = "SELECT * FROM tb_plots WHERE plot_id ='$plot_id'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);

?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="col text-center mt-2">
                <h6><i class='fas fa-solar-panel'></i> <?php echo $result['name']; ?></h6>
            </div>
            <div id="map" style="width:100%;height:35vh;margin:auto"></div>
            <div class="card-body">
                <span style='font-size:16px;background-color: #0f6fff;color:white' class="badge"><i class='fas fa-info-circle'></i> รายละเอียดพื้นที่ </span><br />
                <div class="text-group">
                    <div>
                        <?php
                        echo
                            "<span style='color:#33d;'><i class='fas fa-map-pin'></i> ละติจูด   : </span>" . $result['lat'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-map-pin'></i> ลองจิจูด  :  </span>" . $result['lon'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-mountain'></i> ภูมิลำเนา :  </span>" . $result['address'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-chart-area'></i> พื้นที่ทั้งหมด :  </span>" . number_format($result['area'])  . ' ' . $result['unit'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-home'></i> พักอาศัย : </span>" . number_format($result['home_area']) . ' ' . $result['unit'] . '<br / >',
                            "<span style='color:#33d;'><i class='fas fa-water'></i> แหล่งน้ำ :  </span>" . number_format($result['water_area']) . ' ' . $result['unit'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-solar-panel'></i> การเกษตร :  </span>" . number_format($result['farm_area']) . ' ' . $result['unit'];
                        ?>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-3">
                    <a href="plot_plant.php?plot_id=<?php echo $plot_id ?>" class="btn btn-outline-secondary btn-md btn-block"><i class="fas fa-sign-in-alt"></i> จัดการแปลงเพาะปลูกพืช</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center mt-3">
        <a class="btn btn-primary btn-sm back" href="plot.php"><i class="fas fa-arrow-left"></i> กลับ</a>
    </div>
    <div class="col mb-5"></div>
</body>
<?php include('layout/footer.php') ?>

<script>
    function initMap() {
        var lat = <?php echo $result['lat'] ?>;
        var lon = <?php echo $result['lon'] ?>;

        var myOptions = {
            zoom: 18,
            center: {
                lat: lat,
                lng: lon
            },
            mapTypeId: google.maps.MapTypeId.HYBRID,
            mapTypeControl: false,
            streetViewControl: false
        };
        var map = new google.maps.Map(document.getElementById('map'),
            myOptions);

        let contenString = '<?php echo $result['name']  ?>';

        var infowindow = new google.maps.InfoWindow({
            content: contenString
        });

        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(lat, lon),
            draggalbe: true
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });



    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th" async defer></script>