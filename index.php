<?php
require('inc/inc.php');

\Sys\Db::query("SELECT v.editorId FROM effects_vanilla v LEFT JOIN effects_requiem r ON v.editorId=r.editorId WHERE r.editorId IS NULL ORDER BY editorId");
$r = \Sys\Db::fetchAll();
$r = transform_array($r, 'editorId', 'editorId');


$frm = new myform();
$frm->cellspacing = 5;
$amplify = array('magnitude' => 'magnitude', 'duration' => 'duration', 'both' => 'both');

$frm->add_control('combobox', 'editorId')->loadArray($r);
$frm->add_control('textbox', 'name', 'std');
$frm->add_control('radiobox', 'amplify')->loadArray($amplify);
$frm->add_control('textbox', 'baseCost', 'std');
$frm->add_control('textbox', 'description', 'std');
$frm->fields['description']->strip_tags = false;

if (!$frm->process())
{
    $frm->fields['amplify']->value = key($amplify);
}
else
{
    $r = $frm->getValues();
    if (empty($r['description']))
    {
        $r['description'] = \Sys\Db::selectFieldByField('effects_vanilla', 'description', array('editorId' => $r['editorId']));
    }

    \Sys\Db::insert('effects_requiem', $r);
    header('Location: index.php');
    die;
}

echo '<style>.std { width:350px; } </style>
<div style="width:500px; margin:0 auto">';
$frm->draw();
?>
</div>
<br><br>
<div style="width:1100px; margin:0 auto;">
<?php
maketable("SELECT CONCAT('',r.editorId) AS id,r.name,r.description,v.baseCost AS vBaseCost,r.baseCost as rBaseCost,CONCAT(ROUND(r.baseCost/v.baseCost*100,2),'%') AS dif
FROM `effects_requiem` r INNER JOIN effects_vanilla v ON v.editorId=r.editorId
ORDER BY r.editorId");
?>
</div>