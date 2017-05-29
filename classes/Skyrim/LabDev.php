<?php namespace Skyrim;

use Skyrim\Player\PlayerVanilla as Player;

class LabDev extends Lab
{
    /** @var Effect */
    protected $effect;
    protected $magnitude;
    protected $duration;

    public function __construct($mod, Player $player, Effect $effect, $magnitude, $duration)
    {
        $this->mod = $mod;
        $this->player = $player;

        $this->effect = $effect;
        $this->magnitude = $magnitude;
        $this->duration = $duration;
    }

    public function calc()
    {
        $power = $this->calcPowerFactor($this->effect);
        $total = 0;
        $result = new Result();

        $effect = $this->effect;;

        $magnitude = $this->magnitude;
        if ($effect->isAmplifyMagnitude()) $magnitude = round($magnitude * $power);

        $duration = $this->duration;
        if ($effect->isAmplifyDuration()) $duration = round($duration * $power);

        $cost = $this->calcGold($effect, $magnitude, $duration);
        $total += $cost;

        $result->addEffect(
            $effect->getId(),
            $effect->getEditorId(),
            $effect->createDescriptionText($magnitude, $duration),
            floor($cost)
        );

        $result->setCost(floor($total));
        return $result;
    }
}