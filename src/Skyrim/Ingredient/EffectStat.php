<?php
namespace Skyrim\Ingredient;
use Skyrim\Structure;

class EffectStat extends Structure
{
    protected $id;
    protected $magnitude;
    protected $duration;

    /**
     * @return mixed
     */
    public function getBaseDuration()
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
    public function getBaseMagnitude()
    {
        return $this->magnitude + 0;
    }


}