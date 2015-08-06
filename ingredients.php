<?php
use \Msz\Db;
use \Msz\Forms\Form;
use \Msz\Forms\Control\Select;

require('./inc/inc.php');

$id = request('id');

$ing = getIngredients();
array_walk($ing, function(&$el) {
    if ($el['dlc'] != 'VN') $el['name'] .= ' (' . $el['dlc'] . ')';
});

$ing = transform_array($ing, 'id', 'name');

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

array_walk($r, function (&$el) {
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

drawtable($r);
echo '<br>';

$r = array();
foreach ($eids as $k => $v) {

    if (!isset($v['VN']['magnitude'])) $v['VN']['magnitude'] = '';
    if (!isset($v['RQ']['magnitude'])) $v['RQ']['magnitude'] = '';
    if (!isset($v['PH']['magnitude'])) $v['PH']['magnitude'] = '';
    if (!isset($v['VN']['duration'])) $v['VN']['duration'] = '';
    if (!isset($v['RQ']['duration'])) $v['RQ']['duration'] = '';
    if (!isset($v['PH']['duration'])) $v['PH']['duration'] = '';

    $r[] = array(
        'id' => isset($v['RQ']['id']) ? $v['RQ']['id'] : (isset($v['PH']['id']) ? $v['PH']['id'] : $v['VN']['id']),
        'eid' => $k,
        'VN' => $v['VN']['magnitude'] . '-' . $v['VN']['duration'],
        'RQ' => $v['RQ']['magnitude'] . '-' . $v['RQ']['duration'],
        'PH' => $v['PH']['magnitude'] . '-' . $v['PH']['duration']
    );
}

drawtable($r);

echo '</div>';