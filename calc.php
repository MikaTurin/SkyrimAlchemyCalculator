<?php
require('./inc/inc.php');

use Sys\Db;
use Skyrim\Player\Vanilla as Player;
use Skyrim\Lab;

$perk = array(0, 20, 40, 60, 80, 100);


$ingr = getIngredients();
array_walk(Db::fetchAll(), function($el, $key, &$r) {  $r[0][$el['id']] = $el['name']; }, array(&$ingr));
$ingr = array_merge(array('' => '-'), $ingr);

$frm = new myform();
$frm->cellspacing = 5;

$frm->add_control('textbox', 'skill', 'min')->setValue(15);
$frm->add_control('textbox', 'fortify', 'min')->setValue(0);
$frm->add_control('combobox', 'perkAlchemist')->loadArray(array_combine($perk, $perk));
$frm->add_control('combobox', 'ingr1')->loadArray($ingr);
$frm->add_control('combobox', 'ingr2')->loadArray($ingr);
$frm->add_control('combobox', 'ingr3')->loadArray($ingr);


if ($frm->process()) {

    $player = Player::make($frm->getValues());

    $ingr = array();
    for($i=1;$i<=3;$i++) {
        if (!$frm->getValue('ingr'.$i)) continue;
        $ingr[] = Skyrim\Ingredient::makeFromId($frm->getValue('ingr'.$i));
    }

    try {
        $lab = new Lab($player, $ingr);
    }
    catch (Exception $e) {
        dump($e->getMessage());
    }

    $lab->calc();

}

?>
<head>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<div style="width:500px; margin:0 auto">
<div style="width:1100px; margin:0 auto;">
<?php $frm->draw();?>
</div>
</div>