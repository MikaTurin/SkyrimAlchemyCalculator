<?php
require_once dirname(__DIR__) . '/inc/inc.php';

$mod = Mod::get();
$effects = transform_array(getEffects($mod), 'id', 'editorId');
asort($effects);



$frm = Myform::make('frm')
    ->setCellspacing(5)
    ->addControl(myform_textbox::make2('id')->setClassName('min')->setReadOnly())
    ->addControl(Mod::getSelectObject())
    ->addControl(myform_hidden::make2('oldEffectId1'))
    ->addControl(myform_combobox::make2('effectId1')->loadArray($effects))
    ->addControl(myform_textbox::make2('magnitude1')->setClassName('min'))
    ->addControl(myform_textbox::make2('duration1')->setClassName('min'))
    ->addControl(myform_hidden::make2('oldEffectId2'))
    ->addControl(myform_combobox::make2('effectId2')->loadArray($effects))
    ->addControl(myform_textbox::make2('magnitude2')->setClassName('min'))
    ->addControl(myform_textbox::make2('duration2')->setClassName('min'))
    ->addControl(myform_hidden::make2('oldEffectId3'))
    ->addControl(myform_combobox::make2('effectId3')->loadArray($effects))
    ->addControl(myform_textbox::make2('magnitude3')->setClassName('min'))
    ->addControl(myform_textbox::make2('duration3')->setClassName('min'))
    ->addControl(myform_hidden::make2('oldEffectId4'))
    ->addControl(myform_combobox::make2('effectId4')->loadArray($effects))
    ->addControl(myform_textbox::make2('magnitude4')->setClassName('min'))
    ->addControl(myform_textbox::make2('duration4')->setClassName('min'))
;

if (!$frm->process() && !empty($_REQUEST['id'])) {

    $id = $_REQUEST['id'];
    $ingr = \Skyrim\Ingredient::makeFromId($id, $mod);
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

        \Sys\Db::query($q);
    }

    header('Location: /skyrim/edit/' . basename(__FILE__) . '?id=' . $r['id'] . '&mod=' . $r['mod']);
    die;

}