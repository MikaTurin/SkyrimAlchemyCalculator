<?php namespace Skyrim;

use Skyrim\Forms\Calculator;
use Skyrim\Ingredient\CombineList;
use Skyrim\Player\PlayerFactory;

require('./inc/inc.php');

$mod = Mod::get();

$ingr = array('' => '-');
array_walk(getIngredients(), function($el) use (&$ingr) {
    $ingr[$el['id']] = $el['name'].' ['.$el['id'].']';
});

$frm = new Calculator('flt');
$frm->createFields($mod, transform_array(getEffects($mod), 'id', 'name'), $ingr);

if (!$frm->isSubmited() && !empty($_REQUEST['ingr'])) {
    $frm->getField('ingr1')->setValue($_REQUEST['ingr']);
}
?>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
echo getIndexBlock();
$frm->process();

echo '<div style="width:400px; margin:0 auto;">' . $frm->html() . '</div>';

if ($frm->isSubmited()) {
    
    echo '<div align="center">';

    $showmods = Mod::getMods();
    foreach ($showmods as $calcmod => $modname) {

        $player = PlayerFactory::create($calcmod, $frm->getValues());

        echo '<b>' . $modname . '</b><br>';
        try {
            if ($frm->getValue('magnitude') || $frm->getValue('duration')) {

                $lab = new LabDev($calcmod, $player, Effect::makeFromId($frm->getValue('effect'), $calcmod), $frm->getValue('magnitude'), $frm->getValue('duration'));
                $res = $lab->calc();
            }
            else {

                $list = new CombineList();

                for ($i = 1; $i <= 3; $i++) {
                    if (!$frm->getValue('ingr' . $i)) continue;
                    $list->addId($frm->getValue('ingr' . $i), $calcmod);
                }

                $lab = new Lab($calcmod, $player, $list);
                $res = $lab->calc();
            }

            if (is_object($res) && sizeof($res->getEffects())) {


                foreach ($res->getEffects() as $v) {
                    /** @var Effect $eff */
                    $eff = $v['effect'];
                    echo
                        '<a href="viewByEffect.php?id='.$eff->getId().'&mod='.$calcmod.'">'.$eff->getId().'</a>: '.
                        '['.$eff->getEditorId() . '] ' .
                        $v['description'] .
                        ' (' . $v['cost'] . ' Gold) ('. ($eff->getBaseCost() + 0) . ')<br>';
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
        echo '<br><br>';
    }
    echo '</div>';
}
