<?php

  $rawpic = $_POST['pic'];
  $pic = imagecreatefromstring(base64_decode($rawpic));
  $nbimg = $_POST['img'];
  $img = imagecreatefrompng("../public/img/image" . $nbimg . ".png");

  imagealphablending($img, false);
  imagesavealpha($img, true);
  if ($nbimg == 2)
    imagecopy($pic, $img, 160, 160, 0, 0, 100, 100);
  else
    imagecopy($pic, $img, 10, 10, 0, 0, 100, 100);
  ob_start();
  imagejpeg($pic, null, 100);
  $contents = ob_get_contents();
  ob_end_clean();

  echo json_encode(base64_encode($contents));
  imagedestroy($pic);
  imagedestroy($img);

  // merge the picture from the webcam or uploaded ($_POST['pic']) with one of the three image chosen ($_POST['img'])
  // imagecopy detemines the location of the image on the picture
?>
