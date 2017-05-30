<?php namespace Skyrim;

use Msz\Db;
use Msz\Forms\Control\Hidden;
use Msz\Forms\Control\Select;
use Msz\Forms\Control\Text;
use Msz\Forms\Form;

require_once dirname(__DIR__) . '/inc/inc.php';

$mod = Mod::get();
$effects = transform_array(getEffects($mod), 'id', 'editorId');
asort($effects);



$frm = Form::make('frm')
    ->setCellspacing(5)
    ->addControl(Text::make('id', 'min')->setReadOnly())
    ->addControl(Mod::getSelectObject())
    ->addControl(Hidden::make('oldEffectId1'))
    ->addControl(Select::make('effectId1')->loadArray($effects))
    ->addControl(Text::make('magnitude1', 'min'))
    ->addControl(Text::make('duration1', 'min'))
    ->addControl(Hidden::make('oldEffectId2'))
    ->addControl(Select::make('effectId2')->loadArray($effects))
    ->addControl(Text::make('magnitude2', 'min'))
    ->addControl(Text::make('duration2', 'min'))
    ->addControl(Hidden::make('oldEffectId3'))
    ->addControl(Select::make('effectId3')->loadArray($effects))
    ->addControl(Text::make('magnitude3', 'min'))
    ->addControl(Text::make('duration3', 'min'))
    ->addControl(Hidden::make('oldEffectId4'))
    ->addControl(Select::make('effectId4')->loadArray($effects))
    ->addControl(Text::make('magnitude4', 'min'))
    ->addControl(Text::make('duration4', 'min'))
;

if (!$frm->process() && !empty($_REQUEST['id'])) {

    $id = $_REQUEST['id'];
    $ingr = Ingredient::makeFromId($id, $mod);
    $frm->getField('id')->setValue($id);
    $frm->getField('mod')->setValue($mod);

    $idx = 1;
    foreach ($ingr->getEffects()->getAll() as $effectId => $a) {
        $frm->getField('oldEffectId'.$idx)->setValue($effectId);
        $frm->getField('effectId'.$idx)->setValue($effectId)->draw_value = 0;
        $frm->getField('magnitude'.$idx)->setValue($a->getBaseMagnitude());
        $frm->getField('duration'.$idx)->setValue($a->getBaseDuration());

        $idx ++;
    }

    echo getIndexBlock();
    echo '<h2>'.$ingr->getName().'</h2>';
    echo $frm->html(0, false, true);
}
elseif($frm->process()) {

    $r = $frm->getValues();

    for ($i=1; $i<=4; $i++) {

        $tbl = TBL_INGREDIENTS_EFFECTS;

        $q = "UPDATE {$tbl} SET
                effectId='{$r['effectId'.$i]}',
                magnitude='{$r['magnitude'.$i]}',
                duration='{$r['duration'.$i]}'
             WHERE
                id='{$r['id']}'
                AND dlc='{$r['mod']}'
                AND effectId='{$r['oldEffectId'.$i]}'
        ";

        Db::query($q);
    }

    header('Location: /skyrim/edit/' . basename(__FILE__) . '?id=' . $r['id'] . '&mod=' . $r['mod']);
    die;

}