<?php
require_once 'inc/inc.php';

$editorid = '';
$mod = Mod::get();
$effects = getEffects($mod);

array_walk($effects, function (&$el) {
    $el['name'] = $el['cnt'] . ': '.$el['name'];
});

$r = $id = $cnt = null;
if (!empty($_REQUEST['id'])) {

    $id = $_REQUEST['id'];
    $r = getIngredientsByEffectId($id, $mod);
    $a = getEffect($id, $mod);
    $editorid = $a['editorId'];
    $cost = $a['baseCost'] + 0;
    $cnt = sizeof($r);
}

array_walk($r, function (&$el) use($mod) {
    $id = $el['id'];
    $el['id'] = '<a href="ingredients.php?id='.$el['id'].'">'.$el['id'].'</a>';
    if ($el['dlc'] != 'VN') $el['name'] = $el['name'] . ' (' . $el['dlc'] .')';
    $el['price'] = $el['vPrice'];
    if (Mod::isRequiem()) $el['price'] = $el['rPrice'];
    $el['magnitude'] = $el['magnitude'] + 0;
    $el['duration'] = $el['duration'] + 0;
    $el['edit'] = '<a href="/skyrim/edit/ingredientEffect.php?id=' . $id .'&mod=' . $mod . '" target="_blank">edit</a>';
    unset($el['namedlc'], $el['dlc'], $el['vPrice'], $el['rPrice']);
});

$effects = myform_combobox::make('flt', 'id')
    ->loadArray(transform_array($effects, 'id', 'name'))
    ->setValue($id);

$flt = myform::make('flt')
    ->setMethod('GET')
    ->addControl($effects)
    ->addControl(Mod::getSelectObject());

echo getIndexBlock();
?>
<div style="width:600px; margin:0 auto;" align="center">
    <?php echo $flt->html2();?>
    <?php if ($r): drawtable($r);?>
    <br><small>count: <?php echo $cnt;?>; effect: (<?php echo $id;?>) <?php echo $editorid;?>; cost: <?php echo $cost;?></small>
    <?php endif;?>
</div>

