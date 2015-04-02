<?php
require_once('inc/inc.php');

function grabIngedientsArray()
{
    $grab = 0;
    $file = '/tmp/skyrim.txt';

    if ($grab) {
        $s = grab('http://www.uesp.net/wiki/Skyrim:Alchemy_Effects');
        $fh = fopen($file, 'wb');
        fwrite($fh, $s, strlen($s));
        fclose($fh);
    } else {
        $s = file_get_contents($file);
    }


    $s = preg_replace('/^.*<table class="wikitable sortable">(.*)<\/table>.*$/is', '\1', $s);

    preg_match_all('/.*<tr>(.*)<\/tr>.*/isU', $s, $r);


    //$r = $r[1];
    //array_shift($r);
    //echo $s;

    $rez = array();
    $c = sizeof($r[1]);
    for ($i = 1; $i < $c; $i++) {

        preg_match_all('/<th[^>]+>.*<a[^>]+>(.*)<\/a>.*<small>\(([A-Z0-9]{8})\)<\/small><\/th>/isU', $r[1][$i], $a);
        //dump($a);
        $rez[$i - 1] = array();
        $rez[$i - 1]['name'] = $a[1][0];
        $rez[$i - 1]['id'] = $a[2][0];

        $aa = array();
        preg_match_all('/<td[^>]*>(.+)<\/td>/isU', $r[1][$i], $aa);
        //dump($aa);


        $rez[$i - 1]['cost'] = $aa[1][2];
        $rez[$i - 1]['mag'] = preg_replace('/^([0-9]+)<.*$/is', '\1', $aa[1][3]);
        $rez[$i - 1]['dur'] = preg_replace('/^([0-9]+)<.*$/is', '\1', $aa[1][4]);
        $rez[$i - 1]['gold'] = $aa[1][5];


        $s = preg_replace('/.*<p>(.+)<\/p>.*/is', '\1', $r[1][$i]);
        $a = array_map('trim', explode('<br />', $s));

        $rez[$i - 1]['ingredients'] = array();

        for ($j = 0, $cc = sizeof($a); $j < $cc; $j++) {
            if (empty($a[$j])) continue;
            //dump($a[$j]);
            $aa = array();
            preg_match('/^<a[^>]*>([^>]+)<\/a>.*$/isU', $a[$j], $aa);


            $ingr = array(/*'s' => $a[$j],*/ 'name' => $aa[1], 'dlc' => '');

            if (strpos($a[$j], 'title="Skyrim:Dawnguard"') !== false) $ingr['dlc'] = 'DG';
            if (strpos($a[$j], 'title="Dragonborn:Dragonborn"') !== false) $ingr['dlc'] = 'DB';
            if (strpos($a[$j], 'title="Skyrim:Hearthfire"') !== false) $ingr['dlc'] = 'HF';


            $aa = array();
            preg_match_all('/<font[^>]*><b>([^>]+)<\/b><\/font>[^>]*<a href="[^"]+(Value|Damage)/', $a[$j], $aa);
            //dump($aa);

            if (!empty($aa[0][0])) $ingr[$aa[2][0] == 'Damage' ? 'magnitude' : 'value'] = $aa[1][0];
            if (!empty($aa[0][1])) $ingr[$aa[2][1] == 'Damage' ? 'magnitude' : 'value'] = $aa[1][1];


            $rez[$i - 1]['ingredients'][$j] = $ingr;
            //echo '<hr>';
        }


    }
    return $rez;
}


