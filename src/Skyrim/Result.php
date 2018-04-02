<?php namespace Skyrim;

use Skyrim\Ingredient\CombineList;

class Result
{
    protected $name;
    protected $effects;
    protected $cost;
    /** @var CombineList  */
    protected $ingredients;

    public function setIngredients(CombineList $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    public function getIngredients()
    {
        return $this->ingredients;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function addEffect(Effect $effect, $description, $cost)
    {
        return $this->effects[] = array(
            'effect' => $effect,
            'description' => $description,
            'cost' => $cost
        );
    }

    public function isPure()
    {
        $rez = 0;
        $cnt = 0;
        foreach ($this->effects as $r) {
            /** @var Effect $effect */
            $effect = $r['effect'];
            if ($effect->getPoison()) {
                $rez++;
            }
            $cnt++;
        }
        return ($cnt == 0 || $rez == $cnt);
    }

    public function getEffects()
    {
        return $this->effects;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}