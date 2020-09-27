<?php

include('../../config/conectDB.php');
header('Content-Type: application/json');

$sql = "SELECT pp.plotplant_id, p.plot_id,p.lat,p.lon,p.name,p.area,p.address,p.home_area,p.water_area,
p.farm_area,p.unit,u.firstname,u.lastname, pl.plant_name,pp.amount,pp.unit as u FROM tb_plots p 
LEFT JOIN tb_users u on p.user_id = u.id
LEFT JOIN tb_plotplants pp on p.plot_id = pp.plot_id
LEFT JOIN tb_plants pl on pp.plant_id = pl.plant_id
WHERE p.status='1' AND u.status ='user'
GROUP BY pp.plotplant_id";

$query = mysqli_query($dbcon, $sql);
$resultArray = array();
while ($data = mysqli_fetch_assoc($query)) {
    array_push($resultArray, $data);
}
echo json_encode($resultArray);
