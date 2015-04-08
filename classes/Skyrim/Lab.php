<?php
namespace Skyrim;

use Skyrim\Ingredient\EffectStat;
use Skyrim\Player\Vanilla as Player;

class Lab
{
    protected $fAlchemyIngredientInitMult = 4;
    protected $fAlchemySkillFactor = 1.5;

    /** @var Player */
    protected $player;

    /** @var Ingredient[] */
    protected $ingredients = array();

    protected $ingredientsCount = 0;
    protected $haveResult = 0;
    protected $commonEffects = null;
    /**
     * @param Player $player
     * @param Ingredient[] $ingredients
     * @throws \ErrorException
     */
    public function __construct(Player $player, array $ingredients)
    {
        $this->player = $player;
        $this->ingredientsCount = sizeof($ingredients);

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

    /**
     * @deprecated
     * @return int
     */
    public function isCommonEffects()
    {
        $this->calcCommonEffects();
        return (sizeof($this->commonEffects));
    }

    public function calc()
    {
        $power = $this->calcPowerFactor();
        $this->calcCommonEffects();

        for ($i = 0, $c = sizeof($this->commonEffects); $i < $c; $i++)
        {
            $effect = Effect::makeFromId($this->commonEffects[$i]);
            $effectStat = $this->getIngredientsEffectMaxValues($effect);

            $magnitude = $this->calcMagnitude($effect, $effectStat, $power);
            $duration = $this->calcDuration($effect, $effectStat, $power);

            dump($effect);
            dump($effectStat);
            echo '<b style="color:darkred">'.$effect->createDescriptionText($magnitude, $duration).'</b>';
            echo '<hr>';
        }
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

        $this->commonEffects = array_values($a);
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

    protected function calcPowerFactor()
    {
        return
            $this->fAlchemyIngredientInitMult
            * (1 + ($this->fAlchemySkillFactor - 1) * $this->player->getSkill() / 100)
            * (1 + $this->player->getFortify() / 100)
            * (1 + $this->player->getPerkAlchemist() / 100)
            * (1 + $this->player->getPerkPhysician() / 100)
            * (1 + $this->player->getPerkBenefactor() / 100 + $this->player->getPerkPoisoner() / 100);
    }
}