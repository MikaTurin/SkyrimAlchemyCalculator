<?php namespace Skyrim;

use Msz\Db;
use Msz\Forms\Form;
use Msz\Forms\Control\Select;

require('./inc/inc.php');

$id = request('id');


$ing = getIngredients();
array_walk($ing, function(&$el) {
    if ($el['dlc'] != 'VN') $el['name'] .= ' (' . $el['dlc'] . ')';
});

$ing = transform_array($ing, 'id', 'name');
if (!$id) {
    $id = key($ing);
}


echo getIndexBlock();
echo '<div align="center">';

echo Form::make('flt')
    ->setMethod('GET')
    ->addControl(Select::make('id')->loadArray($ing)->setValue($id))
    ->html2();

$tbl1 = TBL_INGREDIENTS_EFFECTS;
$tbl2 = TBL_EFFECTS;
$tbl3 = TBL_INGREDIENTS;

$q = "
SELECT
    e.id,
    e.editorId,
    e.name,
    e.dlc,
    ie.magnitude,
    ie.duration
FROM
  `{$tbl1}` ie
  LEFT JOIN `{$tbl2}` e ON ie.effectId=e.id AND ie.dlc=e.dlc
WHERE
  ie.id='{$id}'
ORDER BY
  ie.dlc DESC,
  ie.effectId
";

Db::query($q);
$r = Db::fetchAll();

array_walk($r, function (&$el) use (&$mods) {
    $el['id'] = '<a href="viewByEffect.php?id='.$el['id'].'&mod='.$el['dlc'].'">'.$el['id'].'</a>';
    $el['magnitude'] = $el['magnitude'] + 0;
    $el['duration'] = $el['duration'] + 0;
});

$eids = array();
for ($i=0, $c=sizeof($r); $i<$c; $i++) {
    //if ($r[$i]['dlc'] == 'PH') unset($r[$i]);

    $eid = $r[$i]['editorId'];
    $dlc = $r[$i]['dlc'];

    if (!isset($eids[$eid])) $eids[$eid] = array();
    $eids[$eid][$dlc] = $r[$i];
}

$mods = Mod::getMods();
unset($mods['VN']);
$mods = array_keys($mods);
sort($mods);
array_unshift($mods, 'VN');


$obj = Ingredient::makeFromId($id, end($mods));
echo '<b>' . $obj->getName() .' ('. $obj->getId() . ')</b><br>';

foreach ($mods as $mod) {
    echo '<a href="/skyrim/edit/ingredientEffect.php?id=' . $id .'&mod=' . $mod . '" target="_blank">'.$mod.'</a> ';
}
echo '<a href="/skyrim/calc.php?ingr=' . $id .'&mod=RR">CALC</a> ';
echo '<br><br>';

$r = array();
foreach ($eids as $k => $v) {

    $link = '';
    foreach ($mods as $mod) {
        if (isset($v[$mod]['id'])) {
            $link = $v[$mod]['id'];
            break;
        }
    }

    $vals = array();

    foreach ($mods as $mod) {
        if (!isset($v[$mod]['magnitude'])) {
            $v[$mod]['magnitude'] = '';
        }
        if (!isset($v[$mod]['duration'])) {
            $v[$mod]['duration'] = '';
        }

        $vals[$mod] = $v[$mod]['magnitude'] . '-' . $v[$mod]['duration'];
    }

    $r[] = array_merge(array(
        'id' => $link,
        'eid' => $k
    ), $vals);
}

drawtable($r);
$ing = Ingredient::makeFromId($id);
echo '<br><small>id: ' . $id .'<br>name ru: ' . $ing->getName() . '<br>dlc: ' . $ing->getDlc() . '</small>';

echo '</div>';