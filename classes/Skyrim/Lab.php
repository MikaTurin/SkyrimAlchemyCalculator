<?php namespace Skyrim;

use Skyrim\Ingredient\EffectStat;
use Skyrim\Player\Vanilla as Player;

class Lab
{
    protected $fAlchemyIngredientInitMult = 4;
    protected $fAlchemySkillFactor = 1.5;

    protected $mod;

    /** @var Player */
    protected $player;

    /** @var Ingredient[] */
    protected $ingredients = array();

    protected $ingredientsCount = 0;
    protected $haveResult = 0;
    protected $commonEffects = null;

    /**
     * @param string $mod
     * @param Player $player
     * @param Ingredient[] $ingredients
     * @throws \ErrorException
     */
    public function __construct($mod, Player $player, array $ingredients)
    {
        $this->mod = $mod;
        $this->player = $player;
        $this->ingredientsCount = sizeof($ingredients);

        if ($mod == 'RQ') $this->fAlchemySkillFactor = 1.1;

        if (!static::isIngredientCountCorrect($this->ingredientsCount)) {
            throw new \ErrorException('incorrect ingredient count, min 2, max 3');
        }

        foreach ($ingredients as $o) {
            $this->ingredients[$o->getId()] = $o;
        }
    }

    public static function isIngredientCountCorrect($cnt)
    {
        return in_array($cnt, array(2, 3));
    }

    public function calc()
    {
        $this->calcCommonEffects();
        $total = 0;
        $result = new Result();

        for ($i = 0, $c = sizeof($this->commonEffects); $i < $c; $i++) {
            $effect = Effect::makeFromId($this->commonEffects[$i], $this->mod);
            $power = $this->calcPowerFactor($effect);
            $effectStat = $this->getIngredientsEffectMaxValues($effect);

            $magnitude = $this->calcMagnitude($effect, $effectStat, $power);
            $duration = $this->calcDuration($effect, $effectStat, $power);
            $cost = $this->calcGold($effect, $magnitude, $duration);
            $total += $cost;

            $result->addEffect(
                $effect->getId(),
                $effect->getEditorId(),
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

    protected function calcCommonEffects()
    {
        if (!is_null($this->commonEffects)) return;

        $r = array();

        foreach ($this->ingredients as $ingredient) {
            $r[] = $ingredient->getEffects()->getIds();
        }

        $a = array_intersect($r[0], $r[1]);
        if ($this->ingredientsCount > 2) {
            $a = array_merge($a, array_intersect($r[1], $r[2]));
            $a = array_merge($a, array_intersect($r[2], $r[0]));
        }

        $this->commonEffects = array_values(array_unique(array_values($a)));
    }

    protected function getIngredientsEffectMaxValues(Effect $effect)
    {
        $max = 0;
        $record = null;

        foreach ($this->ingredients as $ingredient) {

            if (!$ingredient->getEffects()->exists($effect->getId())) continue;

            $stat = $ingredient->getEffects()->get($effect->getId());

            if ($effect->getAmplify() == 'magnitude') {
                if ($stat->getBaseMagnitude() > $max) {
                    $max = $stat->getBaseMagnitude();
                    $record = $stat;
                }
            }
            else if ($effect->getAmplify() == 'duration') {
                if ($stat->getBaseDuration() > $max) {
                    $max = $stat->getBaseDuration();
                    $record = $stat;
                }
            }
        }

        return $record;
    }

    protected function calcPowerFactor(Effect $effect, $noperk = false)
    {
        if ($noperk) {
            $benefactor = 0;
            $poisoner = 0;
        }
        else {
            $poisoner = $this->player->getPerkPoisoner();
            $benefactor = $this->player->getPerkBenefactor();
            if ($effect->getPoison()) $benefactor = 0;
            if (!$effect->getPoison()) $poisoner = 0;
        }

        $power =
            $this->fAlchemyIngredientInitMult
            * (1 + ($this->fAlchemySkillFactor - 1) * $this->player->getSkill() / 100)
            * (1 + $this->player->getFortify() / 100)
            * (1 + $this->player->getPerkAlchemist() / 100)
            * (1 + $this->player->getPerkPhysician() / 100)
            * (1 + $benefactor / 100 + $poisoner / 100);

        if ($this->mod == 'RQ' && $this->player->getPerkPurity()) {
            $power = $power * 1.8;
        }

        return $power;
    }
}