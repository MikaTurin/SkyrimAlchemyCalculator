<?php
namespace Skyrim\Ingredient;
use Skyrim\Structure;

/**
 * @property-read int $id
 * @property-read float $duration
 * @property-read float $magnitude
 *
 * @package Skyrim\Ingredient
 */
class EffectStat extends Structure
{
    protected $id;
    protected $magnitude;
    protected $duration;

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMagnitude()
    {
        return $this->magnitude;
    }


}