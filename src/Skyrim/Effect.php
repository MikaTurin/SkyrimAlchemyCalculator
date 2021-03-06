<?php namespace Skyrim;

use Msz\Db;

class Effect extends Structure
{
    protected $table = 'effects';

    protected $id;
    protected $editorId;
    protected $name;
    protected $poison;
    protected $description;
    protected $amplify;
    protected $baseCost;
    protected $baseCostOld;
    protected $dlc;
    protected $modded;
    protected $hidden;

    public function __construct(array $r = null)
    {
        parent::__construct($r);
    }

    protected static function read($id, $mod)
    {
        $r = Db::selectRowByField('effects', array('id' => $id, 'dlc' => $mod));

        if (!Db::numRows()) {
            throw new \Exception("cant find effect by id {$id} & mod {$mod}");
        }

        return $r;
    }

    public static function makeFromId($id, $mod = null)
    {
        static $data;

        if (is_null($mod)) $mod = Mod::getDefault();

        if (!is_array($data)) {
            $data = array();
        }
        if (!array_key_exists($mod, $data)) {
            $data[$mod] = array();
        }
        if (!array_key_exists($id, $data[$mod])) {
            $data[$mod][$id] = static::read($id, $mod);
        }

        return new static($data[$mod][$id]);
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