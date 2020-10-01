<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

if (isset($_REQUEST['plot_id'])) {
    $plot_id = $_REQUEST['plot_id'];
    $sql = "SELECT * FROM tb_plots WHERE plot_id='$plot_id'";
    $query = mysqli_query($dbcon, $sql);
    $result = mysqli_fetch_assoc($query);
    $status = 1;
}

$lat =  isset($result['lat']) ? $result['lat'] : '';
$lon = isset($result['lon']) ? $result['lon'] : '';
$name = isset($result['name']) ? $result['name'] : '';
$address = isset($result['address']) ? $result['address'] : '';
$area = isset($result['area']) ? $result['area'] : '';
$home_area = isset($result['home_area']) ? $result['home_area'] : '';
$water_area = isset($result['water_area']) ? $result['water_area'] : '';
$farm_area = isset($result['farm_area']) ? $result['farm_area'] : '';
$status = isset($_REQUEST['plot_id']) ? 1 : 0;
$id = isset($_REQUEST['plot_id']) ? $_REQUEST['plot_id'] : '';

?>


<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2 col-sm-12" id="box">
        <div class="card">
            <div class="col text-center">
                <h5 class="mt-3"><i class="fas fa-book-reader"></i> ลงทะเบียนแปลงเกษตร</h5>
                <hr>
            </div>
            <div class="crad-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="plot_insert_db.php" id="form" method="POST">
                            <!-- parameter -->
                            <input hidden type="text" value="<?php echo $status ?>" name="status">
                            <input hidden type="text" value="<?php echo $id ?>" name="plot_id">

                            <div class="form-group">
                                <div class="col">
                                    <label for="address"><i class="fas fa-map-marked-alt"></i> แผนที่</label>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <input class="form-control" id="locat" type="textbox" placeholder="ค้นหาสถานที่">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" id="search" type="button"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="map" style=" width:100%;height:50vh;margin:auto"></div>

                            </div>
                            <div class="col">
                                <div hidden class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Lat</i></span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo $lat ? $lat : '' ?>" name="lat" id="lat" placeholder="ตัวอย่าง 101.0384" required>
                                </div>
                            </div>
                            <div class="col">
                                <div hidden class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">long</span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo $lon ? $lon : '' ?>" name="lon" id="lon" placeholder="ตัวอย่าง 90.24132" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ชื่อสถานที่</span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo $name ? $name : '' ?>" name="name" id="name" maxlength="150" placeholder="ตัวอย่าง (สวนร้อยรัก)" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ภูมิลำเนา</span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo $address ? $address : '' ?>" name="address" id="address" maxlength="150" placeholder="ตัวอย่าง 27 ต.ท่าอิฐ อ.เมือง จ.อุตรดิตถ์" required>
                                </div>
                            </div>
                            <div class="col">
                                <label for="">การจัดการพื้นที่ (หน่วย : ไร่)</label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">พื้นที่ทั้งหมด</span>
                                    </div>
                                    <input type="number" class="form-control" step="0.01" min="0" value="<?php echo $area ? $area : '' ?>" name="area" id="area" placeholder="ตัวอย่าง 400 " onKeyPress="if(this.value.length==5) return false;" required>
                                </div>
                                <div class="input-group mb-2 mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">พักอาศัย</span>
                                    </div>
                                    <input type="number" class="form-control" step="0.01" min="0" value="<?php echo $home_area  | $result['home_area'] ?>" name="home_area" onKeyPress="if(this.value.length==5) return false;" id="home_area" onchange="checkArea()" placeholder="ตัวอย่าง 1 " required>

                                </div>

                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">แหล่งน้ำ</span>
                                    </div>
                                    <input type="number" class="form-control" step="0.01" min="0" onKeyPress="if(this.value.length==5) return false;" value="<?php echo $water_area | $result['water_area']  ?>" name="water_area" onchange="checkArea()" id="water_area" placeholder="ตัวอย่าง  1" required>

                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">การเกษตร</span>
                                    </div>
                                    <input type="number" class="form-control" step="0.01" min="0" onKeyPress="if(this.value.length==5) return false;" value="<?php echo $farm_area | $result['farm_area'] ?>" name="farm_area" onchange="checkArea()" id="farm_area" placeholder="ตัวอย่าง 400 " required>
                                </div>
                                <div class="input-group mb-1">
                                    <span id='message'></span>
                                </div>
                                <input hidden type="text" name="unit" value="ไร่">
                            </div>
                            <div class="col-md-12 text-center mt-4">
                                <?php $name ? $btn = 'อัพเดตแปลงเกษตร' : $btn = 'ลงทะเบียนแปลงเกษตร' ?>
                                <button type="submit" class="btn btn-outline-secondary btn-md btn-block" id="register" onclick="checkLatLon()" name="register"><i class="fas fa-sign-in-alt"></i> <?php echo $btn ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>

</body>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th" async defer></script>
<script>
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    function goBack() {
        window.history.back()
    }

    $("form#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#register').prop("disabled", true);
        $('#register').html('<i class="fa fa-spinner fa-spin"></i> กำลังโหลด...');
        $.ajax({
            url: 'plot_insert_db.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status == 'update_success') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'อัพเดตข้อมูลสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                        timer: 3000
                    })
                    setTimeout(() => {
                        window.location.reload('plot_from.php?plot_id=' + result.id);
                    }, 1500)
                } else if (result.status == 'register_success') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'ลงทะเบียนสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                    })
                    setTimeout(() => {
                        window.location.replace('plot.php');
                    }, 1500)

                } else if (result.status == "name_duplicate") {
                    $('#register').prop("disabled", false);
                    $('#register').html('<i class="fas fa-sign-in-alt"></i> ' + result.text);
                    Swal.fire({
                        title: 'คำเตือน',
                        text: 'ชื่อซ้ำกัน',
                        icon: 'info',
                        confirmButtonText: 'ปิด',
                    })
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });

    function checkArea() {
        var area = parseInt(document.getElementById("area").value) | 0;
        var home_area = parseInt(document.getElementById("home_area").value) | 0;
        var water_area = parseInt(document.getElementById("water_area").value) | 0;
        var farm_area = parseInt(document.getElementById("farm_area").value) | 0;
        var sum = home_area + water_area + farm_area;
        if (sum > area) {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = '***พื้นที่เกินกว่าทั้งหมด***';
            document.getElementById("register").disabled = true;
        } else {
            document.getElementById('message').innerHTML = null;
            document.getElementById("register").disabled = false;
        }
    }



    function alert() {
        Swal.fire({
            title: 'ไม่พบสถานที่ที่ค้นหา',
            text: 'กรุณากรอกข้อมูลที่จะค้นหาใหม่ด้วยค่ะ',
            icon: 'question',
            confirmButtonText: 'ปิด'
        })
    }

    function checkLatLon() {
        checkArea();
        if (!document.getElementById("lat").value && !document.getElementById("lon").value) {
            Swal.fire({
                title: 'คำเตือน!',
                text: 'กรุณาปักหมุดแผนที่ด้วยค่ะ',
                icon: 'warning',
                confirmButtonText: 'ปิด'
            })
        }
    }

    // config Maps 
    var lat = <?php echo $lat ? $lat : 17.6200886 ?>;
    var lng = <?php echo $lon ? $lon : 100.09929420000003 ?>;
    var status = <?php echo $status ?>;

    function initMap() {
        var map, infowindow, marker, geocoder;
        if (status == 0) {
            var myOptions = {
                zoom: 14,
                center: {
                    lat: lat,
                    lng: lng
                },
                mapTypeId: google.maps.MapTypeId.HYBRID,
                mapTypeControl: false,
                streetViewControl: false
            };
            map = new google.maps.Map(document.getElementById('map'),
                myOptions);

            infoWindow = new google.maps.InfoWindow;

            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    infoWindow.setPosition(pos);
                    marker.setPosition(pos);
                    infoWindow.setContent('ตำแหน่งปัจจุบันของคุณ  \n' + 'lat: \n' + position.coords.latitude + '\n lng: ' + position.coords.longitude);
                    infoWindow.open(map, marker);
                    map.setCenter(pos);
                    $("#lat").val(position.coords.latitude);
                    $("#lon").val(position.coords.longitude);

                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.open(map);
                infoWindow.setContent(browserHasGeolocation ?
                    'กรุณาเปิด GPS เพื่อนระบุตำแหน่งปัจุบัน.' :
                    'เกิดข้อผิดพลาด!');
            }

            marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(17.6200886, 100.09929420000003),
                draggalbe: true
            });

            infowindow = new google.maps.InfoWindow({
                map: map
            });

            google.maps.event.addListener(map, 'click', function(event) {
                infowindow.open(map, marker);
                infowindow.setPosition(event.latLng);
                marker.setPosition(event.latLng);
                $("#lat").val(event.latLng.lat());
                $("#lon").val(event.latLng.lng());
                geocodeLatLng();
            });
            geocoder = new google.maps.Geocoder();
            document.getElementById('search').addEventListener('click', function() {
                geocodeAddress(geocoder, map);
            });

        } else {
            var myOptions = {
                zoom: 17,
                center: {
                    lat: lat,
                    lng: lng
                },
                mapTypeId: google.maps.MapTypeId.HYBRID,
                mapTypeControl: false,
                streetViewControl: false
            };
            map = new google.maps.Map(document.getElementById('map'),
                myOptions);
            let contenString = $("#address").val();

            infowindow = new google.maps.InfoWindow({
                content: contenString
            });
            marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(lat, lng),
                draggalbe: true
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(map, 'click', function(event) {
                infowindow.open(map, marker);
                infowindow.setPosition(event.latLng);
                marker.setPosition(event.latLng);
                $("#lat").val(event.latLng.lat());
                $("#lon").val(event.latLng.lng());
                geocodeLatLng()
            });
            geocoder = new google.maps.Geocoder();
            document.getElementById('search').addEventListener('click', function() {
                geocodeAddress(geocoder, map);
            });
        }

        function geocodeLatLng() {
            var lat = parseFloat($("#lat").val());
            var lon = parseFloat($("#lon").val());
            var latlng = {
                lat: lat,
                lng: lon
            };
            geocoder.geocode({
                'location': latlng
            }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        marker.setPosition(latlng);
                        infowindow.setContent(results[1].formatted_address);
                        infowindow.open(map, marker);
                        let address = results[1].formatted_address;
                        $("#address").val(address);
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }

        function geocodeAddress(geocoder, resultsMap) {
            var address = document.getElementById('locat').value;
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status === 'OK') {
                    infowindow.open(map, marker);
                    infowindow.setContent(results[0].formatted_address);
                    resultsMap.setCenter(results[0].geometry.location);
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $("#lat").val(results[0].geometry.location.lat());
                    $("#lon").val(results[0].geometry.location.lng());
                    $("#address").val(results[0].formatted_address);
                } else {
                    alert();
                }
            });
        }

    }
</script>