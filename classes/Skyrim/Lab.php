<?php namespace Skyrim;

use Skyrim\Ingredient\CombineList;
use Skyrim\Ingredient\EffectStat;
use Skyrim\Player\PlayerVanilla;

class Lab
{
    protected $mod;

    /** @var PlayerVanilla */
    protected $player;

    /** @var CombineList */
    protected $ingredients;

    protected $haveResult = 0;
    protected $commonEffects = null;

    /**
     * @param string $mod
     * @param PlayerVanilla $player
     * @param CombineList $ingredients
     * @throws \Exception
     */
    public function __construct($mod, PlayerVanilla $player, CombineList $ingredients)
    {
        $this->mod = $mod;
        $this->player = $player;
        $this->ingredients = $ingredients;

        if (!$this->ingredients->isCountCorrect()) {
            throw new \ErrorException('incorrect ingredient count, min 2, max 3');
        }
    }
    
    public function calc()
    {
        $this->commonEffects = $this->ingredients->getCommonEffects();
        $total = 0;
        $result = new Result();

        for ($i = 0, $c = sizeof($this->commonEffects); $i < $c; $i++) {
            $effect = Effect::makeFromId($this->commonEffects[$i], $this->mod);
            $power = $this->player->calcPowerFactor($effect);
            $effectStat = $this->ingredients->getEffectMaxValue($effect);

            $magnitude = $this->calcMagnitude($effect, $effectStat, $power);
            $duration = $this->calcDuration($effect, $effectStat, $power);
            $cost = $this->calcGold($effect, $magnitude, $duration);
            $total += $cost;

            $result->addEffect(
                $effect,
                $effect->createDescriptionText($magnitude, $duration),
                floor($cost)
            );
        }

        $result->setCost(floor($total));
        return $result;
    }

    public function calcGold(Effect $effect, $magnitude, $duration)
    {
        $cost = 0;

        /*if ($magnitude && $duration) {
            $cost = $effect->getBaseCost() * pow($magnitude, 1.1) * 0.0794328 * pow($duration, 1.1);
        }
        elseif ($magnitude && $duration == 0) {
            $cost =  $effect->getBaseCost() * pow($magnitude, 1.1);
        }
        elseif ($duration && $magnitude == 0) {
            $cost = $effect->getBaseCost() * 0.0794328 * pow($duration, 1.1);
        }*/



        $magnitudeFactor = 1;
        if ($magnitude > 0 ) $magnitudeFactor = $magnitude;
        $durationFactor = 1;
        if ($duration > 0 ) $durationFactor = $duration / 10;
        $cost = $effect->getBaseCost() * pow($magnitudeFactor * $durationFactor, 1.1);

        return $cost;
    }

    public function calcMagnitude(Effect $effect, EffectStat $effectStat, $power)
    {
        if (!$effect->isAmplifyMagnitude()) return round($effectStat->getBaseMagnitude());

        return round($effectStat->getBaseMagnitude() * $power);
    }

    public function calcDuration(Effect $effect, EffectStat $effectStat, $power)
    {
        if (!$effect->isAmplifyDuration()) return round($effectStat->getBaseDuration());

        return round($effectStat->getBaseDuration() * $power);
    }
}