<?php namespace Skyrim;

use Msz\Db;
use Msz\Forms\Form;

require('./inc/inc.php');

$mod = Mod::get();
$tbl = TBL_EFFECTS;

echo getIndexBlock();
echo '<div align="center">';
echo Form::make('flt')->addControl(Mod::getSelectObject())->setMethod('GET')->html2();


$q = "
SELECT
  e.id,
  e.name,
  sum(if(i.rarity = 1, 1, 0)) AS rarity1,
  sum(if(i.rarity = 2, 1, 0)) AS rarity2,
  sum(if(i.rarity = 3, 1, 0)) AS rarity3,
  sum(if(i.rarity = 4, 1, 0)) AS rarity4,
  sum(if(i.rarity = 5, 1, 0)) AS rarity5,
  COUNT(i.id) AS total,
  e.modded
FROM
  `{$tbl}` e
  LEFT JOIN `ingredients_effects` ie ON ie.effectid=e.id
  LEFT JOIN `ingredients` i ON ie.id=i.id
WHERE
  e.dlc='{$mod}'
  AND ie.dlc='{$mod}'
GROUP BY
  e.id
ORDER BY
  total DESC
";

Db::query($q);
$r = Db::fetchAll();

array_walk($r, function (&$el) use ($mod) {
    $el['id'] = '<a href="viewByEffect.php?id='.$el['id'].'&mod='.$mod.'">' . $el['id'] . '</a>';
    if ($el['modded']) {
        $el['class'] = 'modded';
    }
    unset($el['modded']);
});

drawtable($r);
echo '</div>';