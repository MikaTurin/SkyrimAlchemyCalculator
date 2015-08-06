<?php
namespace Skyrim\Player;

use Skyrim\Structure;

class Vanilla extends Structure
{
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


    public static function make(array $r = null)
    {
        return new static($r);
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
}