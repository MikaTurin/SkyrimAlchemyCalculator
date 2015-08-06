<?php namespace Skyrim;

use Skyrim\Player\Vanilla as Player;
use Mod;
use Myform;

require('./inc/inc.php');

$mod = Mod::get();
$perk = array(0, 20, 40, 60, 80, 100);
if (Mod::isRequiem()) $perk = array(0, 25, 50);

$ingr = array();
array_walk(getIngredients(), function($el, $key, &$r) {
    $r[0][$el['id']] = $el['name'].' ['.$el['id'].']';
}, array(&$ingr));
$ingr = array_merge(array('' => '-'), $ingr);


$frm = new Myform();
$frm->cellspacing = 5;

$frm->addControl(Mod::getSelectObject());

$frm->add_control('textbox', 'skill', 'min')->setValue(15);
$frm->add_control('textbox', 'fortify', 'min')->setValue(0);
$frm->add_control('combobox', 'perkAlchemist')->loadArray(array_combine($perk, $perk));
$frm->add_control('combobox', 'perkBenefactor')->loadArray(array(0 => 0, 25 => 25));
$frm->add_control('combobox', 'perkPoisoner')->loadArray(array(0 => 0, 25 => 25));
$frm->add_control('combobox', 'perkPurity')->loadArray(array(0 => 0, 1 => 1));
$frm->add_control('combobox', 'effect')->loadArray(transform_array(getEffects($mod), 'id', 'name'));
$frm->add_control('textbox', 'magnitude', 'min');
$frm->add_control('textbox', 'duration', 'min');
$frm->add_control('combobox', 'ingr1')->loadArray($ingr);
$frm->add_control('combobox', 'ingr2')->loadArray($ingr);
$frm->add_control('combobox', 'ingr3')->loadArray($ingr);
?>
<head>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<?php
echo getIndexBlock();
$frm->process();
echo '<div style="width:400px; margin:0 auto;">' . $frm->html() . '</div>';

if ($frm->isSubmited()) {

    $player = Player::make($frm->getValues());
    echo '<div align="center">';

    try {
        if ($frm->getValue('magnitude') || $frm->getValue('duration')) {

            $lab = new LabDev($mod, $player, Effect::makeFromId($frm->getValue('effect'), $mod), $frm->getValue('magnitude'), $frm->getValue('duration'));
            $res = $lab->calc();
        }
        else {

            $ingr = array();
            for ($i = 1; $i <= 3; $i++) {
                if (!$frm->getValue('ingr' . $i)) continue;
                $ingr[] = Ingredient::makeFromId($frm->getValue('ingr' . $i), $mod);
            }
            $lab = new Lab($mod, $player, $ingr);
            $res = $lab->calc();
        }

        if (sizeof($res->getEffects())) {
            foreach ($res->getEffects() as $v) {
                echo
                    '<a href="viewByEffect.php?id='.$v['id'].'&mod='.$mod.'">'.$v['id'].'</a>: '.
                    '['.$v['editorId'] . '] ' .
                    $v['description'] .
                    ' (' . $v['cost'] . ' Gold)<br>';
            }
            echo $res->getCost() . ' Gold';
        }
        else {
            echo 'no results';
        }
    }
    catch (\Exception $e) {
        echo $e->getMessage();
    }
    echo '</div>';
}
