<?php namespace Skyrim\Forms;

use Msz\Forms\Control\Base;
use Msz\Forms\Control\Select;
use Msz\Forms\Control\Text;
use Msz\Forms\Form;
use Skyrim\Mod;
use Skyrim\Player\PlayerFactory;

class Calculator extends Form
{
    protected $mod;

    public function createFields($mod, $effects, $ingr)
    {
        $this->mod = $mod;
        $this
            ->setCellspacing(5)
            ->addControl(Mod::getSelectObject())
            ->addControl(Text::make('skill')->setClass('min')->setValue(15))
            ->addControl(Text::make('fortify')->setClass('min')->setValue(0))
            ->addControl(Select::make('perkAlchemist')->loadArray(PlayerFactory::create($mod)->getPerks()))
            ->addControl(Select::make('perkBenefactor')->loadArray(array(0 => 0, 25 => 25)))
            ->addControl(Select::make('perkPoisoner')->loadArray(array(0 => 0, 25 => 25)))
            ->addControl(Select::make('perkPhysician')->loadArray(array(0 => 0, 25 => 25)))
            ->addControl(Select::make('perkPurity')->loadArray(array(0 => 0, 1 => 1)))
            ->addControl(Select::make('effect')->loadArray($effects))
            ->addControl(Text::make('magnitude', 'min'))
            ->addControl(Text::make('duration', 'min'))
            ->addControl(Select::make('ingr1')->loadArray($ingr))
            ->addControl(Select::make('ingr2')->loadArray($ingr))
            ->addControl(Select::make('ingr3')->loadArray($ingr))
            ->addControl(Select::make('stats')
                ->loadArray(array('' => '', 'min' => 'min', 'mid' => 'mid', 'max' => 'max'))->setType(Base::BUTTON)
                ->addStyle('margin-right:5px')
            )
        ;    
    }

    public function process($force = false)
    {
        $is = parent::process($force);

        if ($this->isSubmited()) {
            if ($this->getField('stats')->getValue() == 'min') {
                $this->getField('skill')->setValue(15);
                $this->getField('perkAlchemist')->setValue(0);
                $this->getField('perkBenefactor')->setValue(0);
                $this->getField('perkPoisoner')->setValue(0);
                $this->getField('perkPurity')->setValue(0);
                $this->getField('stats')->setValue('');
            }
            elseif ($this->getField('stats')->getValue() == 'max') {
                $player = PlayerFactory::create($this->mod);
                $this->getField('skill')->setValue(100);
                $this->getField('perkAlchemist')->setValue(end(array_keys($player->getPerks())));
                $this->getField('perkPurity')->setValue(1);
                $this->getField('stats')->setValue('');
            }
            elseif ($this->getField('stats')->getValue() == 'mid') {
                $player = PlayerFactory::create($this->mod);
                $this->getField('skill')->setValue(40);
                $this->getField('perkAlchemist')->setValue(end(array_keys($player->getPerks())));
                $this->getField('perkPurity')->setValue(0);
                $this->getField('stats')->setValue('');
            }
        }

        return $is;

    }


}