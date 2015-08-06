<?php
use \Msz\Db;

function getIngredients($dlc = null)
{
    $fld = null;
    if ($dlc) $fld = array('dlc' => $dlc);
    return Db::selectRowsByField(TBL_INGREDIENTS, $fld, 'name');
}

function getIngredientIdByName($name)
{
    return Db::selectFieldByField(TBL_INGREDIENTS, 'id', array('name' => $name));
}

function getEffect($id, $dlc)
{
    return Db::selectRowByField(TBL_EFFECTS, array('id' => $id, 'dlc' => $dlc));
}

function getEffectIdByName($name, $dlc)
{
    return Db::selectFieldByField(TBL_EFFECTS, 'id', array('name' => $name, 'dlc' => $dlc));
}

function getEffectNameById($id, $dlc)
{
    return Db::selectFieldByField(TBL_EFFECTS, 'name', array('id' => $id, 'dlc' => $dlc));
}

function getIngredientsByEffectId($effect_id, $dlc)
{
    $tbl1 = TBL_INGREDIENTS;
    $tbl2 = TBL_INGREDIENTS_EFFECTS;

    $q = "
    SELECT
      i.*,
      IF(i.dlc<>'',CONCAT(i.name,' <sup>(',i.dlc,')</sup>'),i.name) as namedlc,
      v.magnitude,
      v.duration
    FROM
      {$tbl1} i
      LEFT JOIN {$tbl2} v ON i.id=v.id
    WHERE
      v.effectId='{$effect_id}'
      AND v.dlc = '{$dlc}'
    ORDER BY
      i.rarity
    ";

    Db::query($q);
    return Db::fetchAll();
}

function getEffects($dlc)
{
    $tbl1 = TBL_EFFECTS;
    $tbl2 = TBL_INGREDIENTS_EFFECTS;

    $q = "
    SELECT
      e.id,
      e.editorId,
      e.name,
      e.dlc,
      COUNT(i.id) AS cnt
    FROM
      {$tbl1} e
      LEFT JOIN {$tbl2} i ON e.id=i.effectId
    WHERE
      e.dlc='{$dlc}'
      AND i.dlc='{$dlc}'
    GROUP BY
      e.id
    ORDER BY
      e.name
    ";

    Db::query($q);
    return Db::fetchAll();
}

function request($var)
{
    $id = null;
    if (!empty($_REQUEST[$var])) $id = $_REQUEST[$var];
    return $id;
}

function getIndexBlock()
{
    return '<div align="center" style="margin-bottom:15px"><a href="/skyrim">index</a></div>';
}