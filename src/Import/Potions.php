<?php namespace Import;

use Skyrim\Potion;

class Potions extends Import
{
    public function process($info)
    {
        $r = $this->getArrayFromText($info);
        $rez  = array();

        for ($i=0, $c=sizeof($r); $i<$c; $i++) {
            if ($a = $this->parseString($r[$i])) {
                $rez[] = $a;
            }
        }

        for ($i=0, $c=sizeof($rez); $i<$c; $i++) {

            $obj = new Potion($rez[$i]);
            $obj->insert();
        }

        return $c;
    }

    protected function parseString($s)
    {
        $s = trim($s);
        if (empty($s)) {
            return null;
        }

        $r = explode(';', $s);
        $c = sizeof($r);
        if ($c < 7 && $c > 10) {
            throw new \Exception('cant parse string: '. $s);
        }
        if ($c > 7) {
            return null; //only with one effect
        }

        $a = array();
        $a['id'] = $this->fixDlcFormId($r[0]);
        $a['dlc'] = $this->mod;
        $a['name'] = $r[1];
        $a['weight'] = $r[2] + 0;
        $a['price'] = $r[3] + 0;
        $a['effectId'] = $this->fixDlcFormId($this->getMgefId($r[4]));
        $a['magnitude'] = $r[5] + 0;
        $a['duration'] = $r[6] + 0;


//        for ($i=4; $i<16; $i+=3) {
//            if (!isset($r[$i])) break;
//
//            $eid = $this->fixDlcFormId($this->getMgefId($r[$i]));
//            Effect::makeFromId($eid, $this->mod);
//            $a['effects'][$eid] = array('magnitude' => $r[$i + 1], 'duration' => $r[$i + 2]);
//        }

        return $a;

    }
}