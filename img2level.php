#!/usr/bin/php
<?php

$options['file'] = "img.png";
$options['config'] = "config.php";

$commands = array(
    'f' => 'file',
    'd' => 'dir',
    'h' => 'help',
    't' => 'target',
    'c' => 'config',
);

foreach ($argv as $key => $arg){
    if (substr($arg, 0, 2) == '--'){
        if (strpos($arg, '=')){
            $options[substr($arg, 2, strpos($arg, '=') - 2)]=substr($arg, strpos($arg, '=') + 1);
        }
        else{
            $options[substr($arg, 2)]=true;
        }
    }
    else if (substr($arg, 0, 1) == '-'){
        for ($i = 1; $i < strlen($arg); $i++){
            if (!isset($argv[$key + 1]) || substr($argv[$key + 1], 0, 1) == '-'){
                $options[$commands[substr($arg, $i, 1)]] = true;
            }
            else{
                $options[$commands[substr($arg, $i, 1)]] = $argv[$key + 1];
            }
        }
    }
}

if (@$options['help']){
    print "Available options:
-f <file>, --file=<file> 
    Specifies the source file to convert. Ignored if -d or --dir is used.

-d <directory>, --dir=<directory>
    Converts all the files in the specified directory

-h, --help
    Displays this message

-t <file,directory>, --target=<file,directory> //TODO
    Write the result in the target file or directory.
    If the target is a directory, a file will be created for each source image.

-c <file>, --config=<file>
    Specifies the config file to use

";
    exit;
}

include $options['config'];

$directory = $options['dir'];

if ($options['dir']){
    $result = array();
    $files = scandir($directory);
    foreach($files as $file){
        if (!is_dir($file) && in_array(strtolower(array_pop(explode('.', $file))), array('png', 'gif'))){
            $result[] = img2array($file);
        }
    }
}
else{
    $file = $options['file'];
    $result = img2array($file);
}

print "\n".str_replace(array('],', '},'), array("],\n", "},\n"), json_encode($result))."\n";

function img2array($file){
    global $config;
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
    }

    $level = array('data' => array(), 'objects' => array());

    for ($i=0; $i <$width; $i++){ 
        for ($j=0; $j<$height; $j++){
            $color = imagecolorat($im, $i, $j);
            if (imageistruecolor($im)){
                $color = sprintf('%06s', dechex($color));
            }
            
            $level['data'][$j][$i] = $config['colors'][$color]['value'] ? $config['colors'][$color]['value'] : 0;
            if (isset($config['colors'][$color]['object'])){
                $object = $config['colors'][$color]['object'];
                $object['x'] = $i;
                $object['y'] = $j;
                $level['objects'][] = $object;
            }
        }
    }
    return $level;
}
