<?php namespace Import;

use Skyrim\Effect;
use Skyrim\Ingredient\EffectStat;

class IngredientsEffects extends Import
{
    public function process($info)
    {
        $info = trim($info);
        $info = str_replace("\r", '', $info);
        $r = explode("\n", $info);
        $count = 0;
        $rez  = array();

        for ($i=0, $c=sizeof($r); $i<$c; $i++) {
            if ($a = $this->parseString($r[$i], $count)) {
                $rez[] = $a;
            }
        }

        for ($i=0, $c=sizeof($rez); $i<$c; $i++) {

            $obj = new EffectStat($rez[$i]);
            $obj->insert();
        }

    }

    protected function parseString($s, &$count)
    {
        $s = trim($s);
        if (empty($s)) {
            return null;
        }
        $count ++;
        if ($count > 4) $count = 1;

        $r = explode(';', $s);

        $a = array();
        $a['id'] = $this->fixDlcFormId(trim($r[0]));
        $a['effectId'] = preg_replace('/^.*\[MGEF\:([A-Z0-9]{8})\]\s?$/iU', '$1', $r[2]);
        $a['dlc'] = $this->mod;
        $a['idx'] = $count;
        $a['magnitude'] = $r[3] + 0;
        $a['duration'] = $r[4] + 0;

        try {
            Effect::makeFromId($r['effectId'], $this->mod);
        }
        catch (\Exception $e) {
            dump($e);
        }

        return $a;
    }
}