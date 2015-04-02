<?php
namespace Skyrim;

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

        $c = sizeof($ingredients);
        if ($c < 2 || $c > 3) throw new \ErrorException('incorrect ingredient count, min 1, max 3');

        foreach ($ingredients as $o) {
            $this->ingredientsCount ++;
            $this->ingredients[$o->getId()] = $o;
        }
    }

    public static function isIngredientCountCorrect($cnt)
    {
        return in_array($cnt, array(2, 3));
    }

    public function isCommonEffects()
    {
        $this->calcCommonEffects();
        return (sizeof($this->commonEffects));
    }

    public function calc()
    {
        $this->calcCommonEffects();

        for ($i = 0, $c = sizeof($this->commonEffects); $i < $c; $i++)
        {
            $effect = Effect::makeFromId($this->commonEffects[$i]);
            $r = $this->getIngredientsEffectMaxValues($effect);

            dump($r);
        }

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
                if ($stat->getMagnitude() > $max) {
                    $max = $stat->getMagnitude();
                    $record = $stat;
                }
            }
            else if ($effect->getAmplify() == 'duration') {
                if ($stat->getDuration() > $max) {
                    $max = $stat->getDuration();
                    $record = $stat;
                }
            }
        }

        return $record;
    }
}