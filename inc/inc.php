<?php
$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

require_once('config.php');
require_once('lib.php');


error_reporting(E_ALL);
\Msz\Db::initialize(DBHOST, DBUSER, DBPASS, DBNAME);
\Msz\Db::$save_history = 1;


function dump($r)
{
    echo '<pre>';
    echo htmlspecialchars(print_r($r, true));
    echo '</pre>';
}

function grab($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function transform_array ($r, $key, $value)
{
    $tmp = array ();
    $is_key = strlen ($key);
    $cnt = sizeof ($r);
    for ($i=0; $i<$cnt; $i++)
    {
        if ($is_key) $tmp[$r[$i][$key]] = $r[$i][$value]; else $tmp[$i] = $r[$i][$value];
    }
    return $tmp;
}

/**
 * @deprecated
 * @param string $query
 */
function maketable($query)
{
    static $style;

    if (is_null($style))
    {
        echo '<style>.vTable {border-collapse:collapse; } .vTable td, .vTable th { font-size:10pt; border:1px solid #ccc; font-family: Tahoma, Arial, sans-serif;}</style>';
        $style = 1;
    }
    $result = mysql_query($query) or die(mysql_error()) ;
    $itemnum = mysql_num_rows($result);
    $ishead = 0;

    if($itemnum > 0) {

        echo '<table border="0" cellspacing="0" cellpadding="3" class="vTable">';
        while($items = mysql_fetch_assoc($result)) {
            if (!$ishead)
            {

                $ishead = 1;

                echo '<tr>' ;
                foreach ($items as $k => $v) {
                    echo '<th>' .htmlspecialchars($k). '</th>' ;
                }
                echo '</tr>' ;
            }
            echo '<tr>' ;
            foreach ($items as $v) {
                echo '<td>' .htmlspecialchars($v). '</td>' ;
            }
            echo '</tr>' ;
        };
        echo '</table>';
    }
}

function drawtable(array $r, $width = 0)
{
    static $style;

    if (is_null($style))
    {
        echo '<style>.vTable {border-collapse:collapse; } .vTable td, .vTable th { font-size:10pt; border:1px solid #ccc; font-family: Tahoma, Arial, sans-serif;} .vTable tr:hover { background: #CDEB8B; } .modded {background-color: #FFFF88; }</style>';
        $style = 1;
    }

    $c = sizeof($r);
    if (!$c) return;

    $add = '';
    if ($width) $add = ' style="width:'.$width.'"';
    echo '<table border="0" cellspacing="0" cellpadding="3" class="vTable"'.$add.'>';

    echo '<tr>' ;
    foreach ($r[0] as $k => $v) {
        if ($k == 'class') continue;
        echo '<th>' . $k . '</th>' ;
    }
    echo '</tr>' ;

    for ($i=0; $i<$c; $i++) {

        $class = '';
        if (!empty($r[$i]['class'])) {
            $class = ' class="'.$r[$i]['class'].'"';
        }
        echo '<tr'.$class.'>';
        foreach ($r[$i] as $k => $v) {
            if ($k == 'class') continue;
            echo '<td>' . $v . '</td>';
        };
        echo '</tr>';
    }
    echo '</table>';
}