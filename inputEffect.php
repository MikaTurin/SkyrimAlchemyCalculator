<?php namespace Skyrim;

use Msz\Db;

die;
Db::query("SELECT v.id,v.editorId FROM effects_vanilla v LEFT JOIN effects_requiem r ON v.editorId=r.editorId WHERE r.editorId IS NULL ORDER BY editorId");
$r = Db::fetchAll();
if (Db::numRows()):

    $r = $r[0];
    $frm = new myform();
    $frm->cellspacing = 5;
    $amplify = array('magnitude' => 'magnitude', 'duration' => 'duration', 'both' => 'both');

    $frm->add_control('textbox', 'id')->tag_extra = 'readonly="readonly"';
    $frm->add_control('textbox', 'editorId')->tag_extra = 'readonly="readonly"';
    $frm->add_control('textbox', 'name', 'std');
    $frm->add_control('radiobox', 'amplify')->loadArray($amplify);
    $frm->add_control('textbox', 'baseCost', 'std');
    $frm->add_control('textbox', 'description', 'std');

    $frm->fields['description']->strip_tags = false;

    if (!$frm->process())
    {
        $frm->fields['amplify']->value = key($amplify);
        $frm->fields['id']->value = $r['id'];
        $frm->fields['editorId']->value = $r['editorId'];
    }
    else
    {
        $r = $frm->getValues();
        if (empty($r['description']))
        {
            $r['description'] = Db::selectFieldByField('effects_vanilla', 'description', array('editorId' => $r['editorId']));
        }

        Db::insert('effects_requiem', $r);
        header('Location: effects.php');
        die;
    }

    echo '<style>.std { width:350px; } </style>
    <div style="width:500px; margin:0 auto">';
    $frm->draw();

    ?>
    </div>
    <br><br>
<?php endif;?>