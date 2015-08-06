<?php
namespace Skyrim;
use \Sys\Db;

class Effect extends Structure
{
    protected $id;
    protected $editorId;
    protected $name;
    protected $poison;
    protected $description;
    protected $amplify;
    protected $baseCost;
    protected $dlc;

    public function __construct(array $r = null)
    {
        parent::__construct($r);
    }

    public static function makeFromId($id, $mod = null)
    {
        if (is_null($mod)) $mod = \Mod::getDefault();

        $r = Db::selectRowByField('effects', array('id' => $id, 'dlc' => $mod));

        return new static($r, $mod);
    }

    /**
     * @return mixed
     */
    public function getAmplify()
    {
        return $this->amplify;
    }

    public function isAmplifyMagnitude()
    {
        return ($this->amplify == 'magnitude'); #TODO: add magnitude+duration amplify
    }

    public function isAmplifyDuration()
    {
        return ($this->amplify == 'duration'); #TODO: add magnitude+duration amplify
    }

    /**
     * @return mixed
     */
    public function getBaseCost()
    {
        return $this->baseCost;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getEditorId()
    {
        return $this->editorId;
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
    public function getName()
    {
        return $this->name;
    }

    public function getPoison()
    {
        return $this->poison;
    }

    public function createDescriptionText($magnitude = 0, $duration = 0)
    {
        return str_replace('<dur>', $duration, str_replace('<mag>', $magnitude, $this->getDescription()));
    }
}