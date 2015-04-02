<?php
namespace Skyrim;
use \Sys\Db;

class Effect extends Structure
{
    protected $id;
    protected $editorId;
    protected $name;
    protected $description;
    protected $amplify;
    protected $baseCost;

    public static function makeFromId($id)
    {
        $r = Db::selectRowByField(TBL_EFFECTS, array('id' => $id));

        return new static($r);
    }

    /**
     * @return mixed
     */
    public function getAmplify()
    {
        return $this->amplify;
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
}