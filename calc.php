<?php namespace Skyrim;

use Skyrim\Mod;
use Msz\Forms\Form;
use Msz\Forms\Control\Select;
use Msz\Forms\Control\Text;
use Skyrim\Ingredient\CombineList;
use Skyrim\Player\PlayerFactory;

require('./inc/inc.php');

$mod = Mod::get();

$ingr = array();
array_walk(getIngredients(), function($el, $key, &$r) {
    $r[0][$el['id']] = $el['name'].' ['.$el['id'].']';
}, array(&$ingr));
$ingr = array_merge(array('' => '-'), $ingr);


$frm = Form::make('flt')
    ->setCellspacing(5)
    ->addControl(Mod::getSelectObject())
    ->addControl(Text::make('skill')->setClass('min')->setValue(15))
    ->addControl(Text::make('fortify')->setClass('min')->setValue(0))
    ->addControl(Select::make('perkAlchemist')->loadArray(PlayerFactory::create($mod)->getPerks()))
    ->addControl(Select::make('perkBenefactor')->loadArray(array(0 => 0, 25 => 25)))
    ->addControl(Select::make('perkPoisoner')->loadArray(array(0 => 0, 25 => 25)))
    ->addControl(Select::make('perkPhysician')->loadArray(array(0 => 0, 25 => 25)))
    ->addControl(Select::make('perkPurity')->loadArray(array(0 => 0, 1 => 1)))
    ->addControl(Select::make('effect')->loadArray(transform_array(getEffects($mod), 'id', 'name')))
    ->addControl(Text::make('magnitude', 'min'))
    ->addControl(Text::make('duration', 'min'))
    ->addControl(Select::make('ingr1')->loadArray($ingr))
    ->addControl(Select::make('ingr2')->loadArray($ingr))
    ->addControl(Select::make('ingr3')->loadArray($ingr))
;
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
    //$showmods = array('RQ' => 'RQ');
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

            if (sizeof($res->getEffects())) {


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
