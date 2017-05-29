<?php namespace Skyrim;

class Result
{
    protected $name;
    protected $effects;
    protected $cost;

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