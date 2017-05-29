<?php namespace Skyrim\Player;

use Skyrim\Effect;
use Skyrim\Structure;

class PlayerVanilla extends Structure
{
    const fAlchemyIngredientInitMult = 4;
    const fAlchemySkillFactor = 1.5;

    /**
     * is your character's alchemy skill (15-100)
     * @var int
     */
    protected $skill = 5;

    /**
     * is the sum of all active Fortify Alchemy enchantments
     * @var int
     */
    protected $fortify = 0;

    /**
     * is the enhancement from the Alchemist perk (0, 20, 40, 60, 80, or 100)
     * @var int
     */
    protected $perkAlchemist = 0;

    /**
     * is the enhancement from the Physician perk (0 or 25).
     * This value is only set to 25 if your character has unlocked the perk and it is being applied to a
     * Restore Health, Restore Magicka, or Restore Stamina effect
     * @var int
     */
    protected $perkPhysician = 0;


    /**
     * is the enhancement from the Benefactor perk (0 or 25). This value is only set to 25 if your character has
     * unlocked the perk and it is being applied to a beneficial effect in a POTION.
     * @var int
     */
    protected $perkBenefactor = 0;

    /**
     * is the enhancement from the Poisoner perk (0 or 25). This value is only set to 25 if your character has
     * unlocked the perk and it is being applied to a negative effect in a POISON.
     * @var int
     */
    protected $perkPoisoner = 0;

    protected $perkPurity = 0;


    public function load(array $r)
    {
        parent::load($r);
        $this->setPerkValue();
    }

    public function getPerks()
    {
        return array(0 => 0, 1 => 20, 2 => 40, 3 => 60, 4 => 80, 5 => 100);
    }

    protected function setPerkValue()
    {
        $perks = $this->getPerks();
        if (!array_key_exists($this->perkAlchemist, $perks)) {
            $this->perkAlchemist = end($perks);
        }
        else {
            $this->perkAlchemist = $perks[$this->perkAlchemist];
        }
    }

    /**
     * @return int
     */
    public function getFortify()
    {
        return $this->fortify;
    }

    /**
     * @param $fortify
     * @return $this
     */
    public function setFortify($fortify)
    {
        $this->fortify = $fortify;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerkAlchemist()
    {
        return $this->perkAlchemist;
    }

    /**
     * @param $perkAlchemist
     * @return $this
     */
    public function setPerkAlchemist($perkAlchemist)
    {
        $this->perkAlchemist = $perkAlchemist;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerkBenefactor()
    {
        return $this->perkBenefactor;
    }

    /**
     * @param $perkBenefactor
     * @return $this
     */
    public function setPerkBenefactor($perkBenefactor)
    {
        $this->perkBenefactor = $perkBenefactor;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerkPhysician()
    {
        return $this->perkPhysician;
    }

    /**
     * @param $perkPhysician
     * @return $this
     */
    public function setPerkPhysician($perkPhysician)
    {
        $this->perkPhysician = $perkPhysician;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerkPoisoner()
    {
        return $this->perkPoisoner;
    }


    /**
     * @param $perkPoisoner
     * @return $this
     */
    public function setPerkPoisoner($perkPoisoner)
    {
        $this->perkPoisoner = $perkPoisoner;

        return $this;
    }

    public function getPerkPurity()
    {
        return $this->perkPurity;
    }

    /**
     * @return int
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * @param $skill
     * @return $this
     * @throws \Exception
     */
    public function setSkill($skill)
    {
        if ($skill < 1 && $skill > 100) throw new \Exception('incorrect skill');
        $this->skill = $skill;

        return $this;
    }

    public function calcPowerFactor(Effect $effect)
    {
        $poisoner = $this->getPerkPoisoner();
        $benefactor = $this->getPerkBenefactor();
        if ($effect->getPoison()) $benefactor = 0;
        if (!$effect->getPoison()) $poisoner = 0;

        $power =
            static::fAlchemyIngredientInitMult
            * (1 + (static::fAlchemySkillFactor - 1) * $this->getSkill() / 100)
            * (1 + $this->getFortify() / 100)
            * (1 + $this->getPerkAlchemist() / 100)
            * (1 + $this->getPerkPhysician() / 100)
            * (1 + $benefactor / 100 + $poisoner / 100);

        return $power;
    }
}