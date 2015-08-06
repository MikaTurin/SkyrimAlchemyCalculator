<?php
use Msz\Db;
use Msz\Forms\Form;

require('inc/inc.php');
$tbl = TBL_EFFECTS;
$mod = Mod::get();

$q = "
SELECT
  r.id,
  CONCAT('',r.editorId) AS editorId,
  r.name,
  r.description,
  v.baseCost AS PHcost,
  r.baseCost as {$mod}cost,
  ROUND(r.baseCost/v.baseCost*100,2) AS dif
FROM
  `{$tbl}` r
  INNER JOIN `{$tbl}` v ON r.editorId=v.editorId
WHERE
  r.dlc='{$mod}'
  AND v.dlc='PH'
ORDER BY
  r.editorId
  /*dif DESC*/
";

Db::query($q);
$r = Db::fetchAll();

array_walk($r, function (&$el) use ($mod) {
    $el['id'] = '<a href="viewByEffect.php?id='.$el['id'].'&mod='.$mod.'">' . $el['id'] . '</a>';
    $el['dif'] = $el['dif'] . '%';
});

echo getIndexBlock();
?>
<div style="width:1100px; margin:0 auto;" align="center">
<?php
echo Form::make('flt')->addControl(Mod::getSelectObject())->setMethod('GET')->html2();

drawtable($r);
?>
</div>