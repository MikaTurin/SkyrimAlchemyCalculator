<?php namespace Skyrim;

use Skyrim\Ingredient\CombineList;
use Skyrim\Player\PlayerVanilla;

class MaxCost
{
    protected $mod;
    protected $player;
    protected $pureonly;

    public function __construct($mod, PlayerVanilla $player, $pureonly = false)
    {
        $this->player = $player;
        $this->mod = $mod;
        $this->pureonly = $pureonly;
        set_time_limit(300);
    }

    /**
     * @param array $ids
     * @return Result[]
     * @throws \Exception
     */
    public function calculate(array $ids)
    {
        $rez = $this->getCombineLists($ids);
        unset($ids);

        if (sizeof($rez)) {
            usort($rez, array($this, 'sort'));
            $rez = array_slice($rez, 0, 100);
        }

        return $rez;
    }

    protected function sort($a, $b)
    {
        if ($a[1] == $b[1]) {
            return 0;
        }
        return ($a[1] > $b[1]) ? -1 : 1;
    }

    protected function getCombineLists($ids)
    {
        $rez = array();
        $list1 = $ids;

        foreach ($ids as $id1 => $c1) {
            unset($list2, $list3);
            $list2 = $list1;
            $list3 = $list1;
            unset($list2[$id1], $list3[$id1]);

            foreach ($list2 as $id2 => $c2) {
                unset($list3[$id2]);

                foreach ($list3 as $id3 => $c3) {

                    //echo '<br>';
                    //dump($i. '. ' . memory_get_usage());

                    if ($tmp = $this->calc($id1, $id2, $id3)) {
                        $rez[] = $tmp;
                        if (sizeof($rez) > 500) {
                            usort($rez, array($this, 'sort'));
                            $rez = array_slice($rez, 0, 100);
                        }
                    }
                }
            }
            unset($list1[$id1]);

        }
        return $rez;
    }


    protected function calc($id1, $id2, $id3)
    {
        $cl = new CombineList();
        $cl->add(Ingredient::makeFromId($id1, $this->mod));
        $cl->add(Ingredient::makeFromId($id2, $this->mod));
        $cl->add(Ingredient::makeFromId($id3, $this->mod));

        $lab = new Lab($this->mod, $this->player, $cl);
        if ($tmp = $lab->calc()) {
            if ($this->pureonly && !$tmp->isPure()) {
                return null;
            }
            return array(join(', ', $tmp->getIngredients()->getNames()), $tmp->getCost());
        }
        return null;
    }
}