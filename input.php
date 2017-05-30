<?php namespace Skyrim;

use Msz\Db;

die;
require('./inc/inc.php');

$mod = 'RQ';

$q = "
  SELECT
    i.*,
    COUNT(p.id) AS ecnt
  FROM
    `ingredients` i
    LEFT JOIN `ingredients_effects` p ON i.id=p.id AND p.dlc='RQ'
  WHERE
    i.name<>''
    AND i.dlc<>'DB'
    AND i.dlc<>'HF'
  GROUP BY
    i.id
  HAVING
    COUNT(p.id)<>4
  ORDER BY
    i.name
  LIMIT
    1
";

Db::query($q);

$id = Db::fetchField('id');
$ingredient = Db::fetchField('name');
$ecnt = Db::fetchField('ecnt');

$effects = transform_array(getEffects('RQ'), 'id', 'editorId');
array_walk($effects, function (&$el) {
    if (substr($el, 0, 4) == 'Alch') $el = substr($el, 4);
    //if (substr($el, 0, 2) == 'PT') $el = '+'.$el;
});

asort($effects);

$frm = new myform();
$frm->cellspacing = 5;

$frm->add_control('textbox', 'id', 'min')->setReadOnly();
$frm->add_control('combobox', 'effectId', 'min')->loadArray($effects);
$frm->add_control('textbox', 'magnitude', 'min')->tag_extra = 'autocomplete=off';
$frm->add_control('textbox', 'duration', 'min')->tag_extra = 'autocomplete=off';

if (!Db::selectFieldByField(TBL_INGREDIENTS, 'rPrice', array('id' => $id))) {
    $frm->add_control('textbox', 'vprice', 'min')->tag_extra = 'autocomplete=off';
    $frm->add_control('textbox', 'rprice', 'min')->tag_extra = 'autocomplete=off';
}




if ($frm->process())
{
    $r = $frm->getValues();
    $price = array('vprice' => $r['vprice'], 'rprice' => $r['rprice']);;
    unset($r['vprice'], $r['rprice']);

    if (empty($r['magnitude']) && empty($r['duration']))
    {
        $stats = Db::selectRowByField(TBL_INGREDIENTS_EFFECTS, array('id' => $id, 'effectid' => $r['effectId'], 'dlc' => $mod));
        if (empty($stats['magnitude']) && empty($stats['duration'])) die('cant get stats data from vanilla!');
        $r['magnitude'] = $stats['magnitude'];
        $r['duration'] = $stats['duration'];
    }

    $r['dlc'] = $mod;
    Db::insert(TBL_INGREDIENTS_EFFECTS, $r);

    if (!empty($price['rprice'])) Db::update(TBL_INGREDIENTS, array('id' => $r['id']), $price);

    header('Location: /skyrim/input.php');
    die;
}

$frm->fields['id']->setValue($id);

echo '<h2>'.$ingredient.' ('. $ecnt . ')</h2>';
$frm->draw();