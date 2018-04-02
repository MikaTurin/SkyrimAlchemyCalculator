<?php namespace Skyrim\Ingredient;

use Skyrim\Effect;
use Skyrim\Ingredient;

class CombineList
{
    /** @var Ingredient[]  */
    protected $list = array();

    public function add(Ingredient $ingredient)
    {
        $this->list[$ingredient->getId()] = $ingredient;
        return $this;
    }

    public function addId($id, $mod)
    {
        $this->list[$id] = Ingredient::makeFromId($id, $mod);
        return $this;
    }

    public function count()
    {
        return sizeof($this->list);
    }

    public function getNames()
    {
        $rez = array();
        foreach ($this->list as $ingredient) {
            $rez[] = $ingredient->getName();
        }
        return $rez;
    }

    public function isCountCorrect()
    {
        return in_array($this->count(), array(2, 3));
    }

    public function getCommonEffects()
    {
        $r = array();

        foreach ($this->list as $ingredient) {
            $r[] = $ingredient->getEffects()->getIds();
        }

        $a = array_intersect($r[0], $r[1]);
        if ($this->count() > 2) {
            $a = array_merge($a, array_intersect($r[1], $r[2]));
            $a = array_merge($a, array_intersect($r[2], $r[0]));
        }

        return array_values(array_unique(array_values($a)));
    }

    /**
     * @param Effect $effect
     * @return EffectStat|null
     * @throws \ErrorException
     */
    public function getEffectMaxValue(Effect $effect)
    {
        $max = 0;
        $record = null;

        foreach ($this->list as $ingredient) {

            if (!$ingredient->getEffects()->exists($effect->getId())) continue;

            $stat = $ingredient->getEffects()->get($effect->getId());

            if ($effect->getAmplify() == 'magnitude') {
                if ($stat->getBaseMagnitude() > $max) {
                    $max = $stat->getBaseMagnitude();
                    $record = $stat;
                }
                elseif ($stat->getBaseMagnitude() == $max && $stat->getBaseDuration() > $record->getBaseDuration()) {
                    $record = $stat;
                }

            }
            else if ($effect->getAmplify() == 'duration') {
                if ($stat->getBaseDuration() > $max) {
                    $max = $stat->getBaseDuration();
                    $record = $stat;
                }
                elseif ($stat->getBaseDuration() == $max && $stat->getBaseMagnitude() > $record->getBaseMagnitude()) {
                    $record = $stat;
                }
            }
        }

        return $record;
    }
}