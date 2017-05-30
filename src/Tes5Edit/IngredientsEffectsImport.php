<?php namespace Tes5Edit;

use Skyrim\Ingredient\EffectStat;

class IngredientsEffectsImport
{
    protected $mod;

    public function __construct($mod)
    {
        $this->mod = $mod;
    }

    public function process($info)
    {
        $info = trim($info);
        $info = str_replace("\r", '', $info);
        $r = explode("\n", $info);
        $count = 0;

        for ($i=0, $c=sizeof($r); $i<$c; $i++) {
            $this->parseString($r[$i], $count);
        }

    }

    protected function parseString($s, &$count)
    {
        $s = trim($s);
        if (empty($s)) {
            return;
        }
        $count ++;
        if ($count > 4) $count = 1;

        $r = explode(';', $s);

        $r['id'] = trim($r[0]);
        if (substr($r['id'], 0, 2) != '00') $r['id'] = 'xx' . substr($r['id'], 2);
        $r['effectId'] = preg_replace('/^.*\[MGEF\:([A-Z0-9]{8})\]\s?$/iU', '$1', $r[2]);
        $r['dlc'] = $this->mod;
        $r['idx'] = $count;
        $r['magnitude'] = $r[3] + 0;
        $r['duration'] = $r[4] + 0;


        unset($r[0], $r[1], $r[2], $r[3], $r[4], $r[5]);

        $obj = new EffectStat($r);
        $obj->insert();

    }
}