<?php

$file = "img.png";

$size = getimagesize($file);
$width = $size[0];
$height = $size[1];

switch(array_pop(explode('.', $file))){
    case 'png':
        $im = imagecreatefrompng($file);
        break;
    case 'gif':
        $im = imagecreatefromgif($file);
        break;
    case 'jpg':
    case 'jpeg':
        $im = imagecreatefromjpg($file);
        break;
}

$level = array();

for ($i=0; $i <$width; $i++){ 
    for ($j=0; $j<$height; $j++){
        $rgb = imagecolorat($im, $i, $j);
	$level[$j][$i] = (int)!(bool) $rgb;
        //$color = imagecolorsforindex($im, $rgb);
    }
}

print "\n".json_encode($level)."\n";
?>
