<?php

$icon =  $_GET['icon'];
$filename = "../../images/plants/" . $icon;
// $filename = __DIR__ . "/../../images/plants/plants5f673bd474fc0.jpg";

$image_s = imagecreatefromstring(file_get_contents($filename));
$width = imagesx($image_s);
$height = imagesy($image_s);

$newwidth = 300;
$newheight = 300;

$image = imagecreatetruecolor($newwidth, $newheight);
imagealphablending($image, true);
imagecopyresampled($image, $image_s, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

//create masking
$mask = imagecreatetruecolor($newwidth, $newheight);

$transparent = imagecolorallocate($mask, 255, 0, 0);
imagecolortransparent($mask, $transparent);

imagefilledellipse($mask, $newwidth / 2, $newheight / 2, $newwidth, $newheight, $transparent);

$red = imagecolorallocate($mask, 0, 0, 0);
imagecopymerge($image, $mask, 0, 0, 0, 0, $newwidth, $newheight, 100);
imagecolortransparent($image, $red);
imagefill($image, 0, 0, $red);

// Allocate green color to image 
$border = imagecolorallocate($mask, 255, 255, 255);

// Function to draw the circle 
for ($i = 0; $i <= 20; $i++) {
    imageellipse($image, 150, 150, $newwidth - $i, $newheight - $i, $border);
}

//output, save and free memory
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
imagedestroy($mask);
