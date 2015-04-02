<?php
use \Sys\Db;

define('TBL_INGREDIENTS', 'ingredients');
define('TBL_INGREDIENTS_STATS', 'ingredients_vanilla');
define('TBL_EFFECTS', 'effects_vanilla');

function getIngredients()
{
    Db::query("SELECT id,name FROM ingredients ORDER BY name");
    return Db::fetchAll();
}

function getIngredientIdByName($name)
{
    return Db::selectFieldByField(TBL_INGREDIENTS, 'id', array('name' => $name));
}

function getEffectIdByName($name)
{
    return Db::selectFieldByField(TBL_EFFECTS, 'id', array('name' => $name));
}

function getIngredientsByEffectId($effect_id)
{
    $tbl = TBL_INGREDIENTS;
    $tbl2 = TBL_INGREDIENTS_STATS;

    $q = "
    SELECT
      i.id,
      IF(i.dlc<>'',CONCAT(i.name,' <sup>(',i.dlc,')</sup>'),i.name) as name,
      v.magnitude,
      v.duration
    FROM
      {$tbl} i
      LEFT JOIN {$tbl2} v ON i.id=v.id
    WHERE
      v.effectId='{$effect_id}'
    ORDER BY
      i.name
    ";

    Db::query($q);
    return Db::fetchAll();
}

function getEffects()
{
    $tbl1 = TBL_EFFECTS;
    $tbl2 = TBL_INGREDIENTS_STATS;

    $q = "
    SELECT
      e.id,
      e.editorId,
      e.name,
      COUNT(i.id) AS count
    FROM
      {$tbl1} e
      LEFT JOIN {$tbl2} i ON e.id=i.effectId
    GROUP BY
      e.id
    ORDER BY
      e.name
    ";

    Db::query($q);
    return Db::fetchAll();
}
