<?php namespace Skyrim;

use Msz\Db;

require_once('inc/inc.php');

$tbl = TBL_EFFECTS;

$q = "
SELECT
  *
FROM
  `{$tbl}` r  
WHERE
  dlc='RQ'
  AND r.id NOT IN (SELECT id FROM `{$tbl}` a WHERE r.id=a.id AND a.dlc='RR')
ORDER BY
  r.editorId
  /*dif DESC*/
";

Db::query($q);
$c = Db::numRows();


for ($i=0; $i<$c; $i++) {
    $r = Db::fetchAssoc();
    $r['dlc'] = 'RR';

    $e = new Effect($r);
    dump($e);
    $e->insert();
    die;
}


