<?php namespace Skyrim;

use Msz\Forms\Control\Number;
use Msz\Forms\Control\Select;
use Msz\Forms\Form;
use Skyrim\Player\PlayerFactory;

require('./inc/inc.php');

$mod = Mod::get();
$player = PlayerFactory::create($mod);

$frm = new Form('frm');
$modselect = Mod::getSelectObject()->addStyle('margin-right:5px; height:19px');
$frm->addControl($modselect);
$skill = Number::make('skill')->setMin('1')->setMax(100)->addStyle('width:40px; height:19px; margin-right:5px');
$frm->addControl($skill);
$perks = Select::make('perkAlchemist')->loadArray($player->getPerks())->addStyle('height:19px; margin-right:5px');
$frm->addControl($perks);
$pure = Select::make('pure')->loadArray(array(0 => 'all', 1 => 'pure only'))->addStyle('height:19px; margin-right:5px');
$frm->addControl($pure);

$r = getIngredients(null, 'nameru');
$cnt = sizeof($r);
$ww = 220;
$cols = 5;
$rows = ceil($cnt / $cols);
for ($i=0; $i<$cnt; $i++) {
    $name = 'ing' . $r[$i]['id'];
    $frm->addControl(Number::make($name)->setTagExtra('id="' . $name . '"'));
}


if (!$frm->process()) {
    $skill->setValue(15);
}


echo getIndexBlock();
echo $frm->begin();
echo '<div align="center">' . $skill->html() . $perks->html() . $modselect->html() . $pure->html() . $frm->htmlSubmit('ok') . '</div>';
echo '<div align="center" style="margin:0 auto; padding-top:7px; width:' . ($ww * $cols) . 'px;">';

for ($i=0; $i<$cols; $i++) {
    echo '<div style="float:left; width:'.$ww.'px" align="left">';
    for ($j=0; $j<$rows; $j++) {
        $idx = (int)$rows * $i + $j;
        if (!isset($r[$idx])) {
            continue;
        }
        $name = 'ing' . $r[$idx]['id'];


        /** @var Number $inp */
        $inp = $frm->getField($name);
        $inp->addStyle('width:38px; margin-right:2px; padding:0 0 0 3px; font-size:11px;')->setMin(0)->setMax(99);
        

        $dlc = '';
        if ($r[$idx]['dlc'] != 'VN') {
            $dlc = ' (' . $r[$idx]['dlc'] .')';
        }
        echo '<div style="padding:1px 0; font-size:11px;">' . $inp->html() . '<label for="'.$name.'">'. $r[$idx]['nameru'] . $dlc .  '</label></div>';
    }
    echo '</div>';
}
echo '<div style="clear: both"></div></div>';
echo $frm->end();

if ($frm->isSubmited()) {

    $start = microtime(true);
    $r = array();
    array_walk($_POST, function ($v, $k) use (&$r) {
        if (empty($v) || substr($k, 0, 3) != 'ing') {
            return;
        }
        $id = substr($k, 3);
        $r[$id] = $v;
    });

    $player->setSkill($skill->getValue())->setPerkAlchemist($perks->getValue());
    $cost = new MaxCost($mod, $player, $pure->getValue());
    $max = $cost->calculate($r);;

    echo '<div align="center">';
    foreach ($max as $result) {
        echo $result[0] . ' (' . $result[1] . ' gold)<br>';
    }

    echo '<br>time: ' . number_format(microtime(true) - $start, 8, '.', '') . '<br>';

    echo '</div>';
}

