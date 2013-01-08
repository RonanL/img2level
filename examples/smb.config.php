<?php
$config = array(
  'colors' => array(
    //Empty
    '5c94fc' => array('value' => 0), //sky
    'ffffff' => array('value' => 1), //cloud

    //Unbreakable
    '000000' => array('value' => 10), //ground
    '208600' => array('value' => 11), //tube
    'c96300' => array('value' => 12), //unbreakable brick

    //Interactive background
    'ff7e00' => array('value' => 20), //breakable brick
    'ffff00' => array('value' => 21), //bonus brick

    //Monsters
    'ff0000' => array('value' => 0, 'object' => array('type' => 'mushroom')),
    '3cff00' => array('value' => 0, 'object' => array('type' => 'turtle')),

    //Player related
    'c600ff' => array('value' => 0, 'object' => array('type' => 'player_start')),
    '84ff00' => array('value' => 0, 'object' => array('type' => 'finish_flag')),
  ),
);