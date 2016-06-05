<?php
namespace Skyrim;

use Skyrim\Ingredient\EffectStat;

require_once('inc/inc.php');


$lab = new Lab(Player\Base::make(), array(
    //Ingredient::makeFromId('0006ABCB'), // Canis Root
    //Ingredient::makeFromId('00034D31'), // Elves Ear
    //Ingredient::makeFromId('0004DA25'), // Blisterwort
    Ingredient::makeFromId('00106E1A'), // River Betty
    Ingredient::makeFromId('00059B86'), // Nirnroot
));

$lab->calc();




dump($lab);