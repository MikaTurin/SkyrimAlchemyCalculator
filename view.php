<?php
require_once 'inc/inc.php';


$effects = getEffects();

$r = $id = $cnt = null;
if (!empty($_REQUEST['id'])) {

    $id = $_REQUEST['id'];
    $r = getIngredientsByEffectId($id);
    $cnt = sizeof($r);
}

?>
<div style="width:500px; margin: 50px auto;" align="center">
    <form style="margin:0; padding:0;">
    <select name="id">
        <?php foreach ($effects as $v) :?>
            <option value="<?php echo $v['id'];?>"<?php echo ($v['id'] == $id) ? ' selected' : '';?>><?php echo $v['name'] . ' ('. $v['count'] .')';?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="show">
    </form>
    <br>

    <?php if ($r) drawtable($r);?>

    <small>count: <?php echo $cnt;?>; effect: <?php echo $id;?>;</small>
</div>

