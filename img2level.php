#!/usr/bin/php
<?php

$options['file'] = "img.png";

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
    Specifies the source file to convert

-d <directory>, --dir=<directory> //TODO
    Converts all the files in the specified directory

-h, --help
    Displays this message

-t <file,directory>, --target=<file,directory> //TODO
    Write the result in the target file or directory.
    If the target is a directory, a file will be created for each source image.

-c <file>, --config=<file> //TODO
    Specifies the config file to use

";
    exit;
}

$file = $options['file'];

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

print "\n".str_replace('],', "],\n", json_encode($level))."\n";
