<?php
include('auth.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

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
                        <h3 class="text"><i class="far fa-map"></i> แผนที่แปลงเกษตร</h3>
                        <hr>
                        <div class="card">
                            <div class="col-md-6">
                                <div class="input-group mt-3">
                                    <input class="form-control" id="keyword" type="text" placeholder="ค้นหาสถานที่">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="search()" id="search" type="button"><i class="fas fa-search"></i></button>
                                    </div>

                                </div>
                            </div>

                            <div id="map" style=" width:100%;height:100vh;margin:auto" class="mt-3"></div>

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
    <!-- end main wrapper -->
</body>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th" async defer></script>
<script type="text/javascript">
    var map, info;
    var markers = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 17.6200886,
                lng: 100.09929420000003
            },
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            mapTypeControl: false,
            streetViewControl: false
        });
        seleteLocation()
    }


    function seleteLocation() {
        $.ajax({
            type: "POST",
            url: "json_location.php",
        }).done(function(text) {
            var json = text;
            console.log(json);

            for (var i = 0; i < json.length; i++) {
                var lat = json[i].lat;
                var lng = json[i].lon;
                var name = json[i].name;
                var area = json[i].area;
                var address = json[i].address;
                var home_area = json[i].home_area;
                var water_area = json[i].water_area;
                var farm_area = json[i].farm_area;
                var unit = json[i].unit;
                var full_name = json[i].firstname + ' ' + json[i].lastname;
                var LatLng = new google.maps.LatLng(lat, lng);

                var modal = '<div id="content">' +
                    '<div id="siteNotice">' +
                    '</div>' +
                    '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                    '<div id="bodyContent">' +
                    '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                    '<span style="color:#33d;"> เจ้าของแปลง :  </span> ' + full_name + '<br>' +
                    '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + '<br>' +
                    '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + unit + '<br>' +
                    '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + unit + '<br>' +
                    '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + '<br>' +
                    '</div>' +
                    '</div>';
                var markeroption = {
                    animation: google.maps.Animation.DROP,
                    map: map,
                    html: modal,
                    position: LatLng
                };
                var data = {
                    modal: modal,
                    location: LatLng
                }

                info = new google.maps.InfoWindow();
                marker = new google.maps.Marker(markeroption);

                google.maps.event.addListener(marker, 'click', function(e) {

                    info.setContent(this.html);
                    info.open(map, this);
                });

            }

        });
    }

    function search() {
        var keyword = $('#keyword').val();
        if (keyword.length === 0) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลที่จะค้นหาด้วยค่ะ',
                icon: 'error',
                confirmButtonText: 'ปิด'
            })
        } else {
            $.ajax({
                type: "POST",
                data: {
                    keyword: keyword
                },
                url: "json_search.php",
            }).done(function(text) {
                var json = text;
                if (json.length == 0) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่พบข้อมูลที่ค้นหา',
                        icon: 'warning',
                        confirmButtonText: 'ปิด'
                    })
                } else {
                    for (var i = 0; i < json.length; i++) {
                        var lat = json[i].lat;
                        var lng = json[i].lon;
                        var name = json[i].name;
                        var area = json[i].area;
                        var address = json[i].address;
                        var home_area = json[i].home_area;
                        var water_area = json[i].water_area;
                        var farm_area = json[i].farm_area;
                        var unit = json[i].unit;
                        var LatLng = new google.maps.LatLng(lat, lng);
                        var modal = '<div id="content">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                            '<div id="bodyContent">' +
                            '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                            '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + '<br>' +
                            '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + unit + '<br>' +
                            '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + unit + '<br>' +
                            '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + '<br>' +
                            '</div>' +
                            '</div>';
                        var markeroption = {
                            map: map,
                            html: modal,
                            position: LatLng
                        };
                        info = new google.maps.InfoWindow();
                        marker = new google.maps.Marker(markeroption);
                        info.setContent(modal);
                        info.open(map, marker);

                    }
                }

            });
        }

    }
</script>