<?php namespace Skyrim\Player;

use Skyrim\Effect;

class PlayerRequiem extends PlayerVanilla
{
    const fAlchemySkillFactor = 1.1;

    public function getPerks()
    {
        return array(0 => 0, 1 => 25, 2 => 50);
    }
    
    public function calcPowerFactor(Effect $effect)
    {
        $this->perkPhysician = 0;
        $power = parent::calcPowerFactor($effect);

        if ($this->getPerkPurity()) {
            $power = $power * 1.8;
        }
        return $power;
    }
}