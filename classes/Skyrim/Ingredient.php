<?php
namespace Skyrim;
use \Sys\Db;
use \Skyrim\Ingredient\EffectsList;

class Ingredient
{
    protected $id;
    protected $name;
    protected $dlc;

    /** @var EffectsList */
    protected $effects;

    public function __construct(array $r)
    {
        if (sizeof($r) != 4) throw new \ErrorException('incorrect ingredient array');

        $this->id = $r[0]['id'];
        $this->name = $r[0]['name'];
        $this->dlc = $r[0]['dlc'];

        $this->effects = new EffectsList($r);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDlc()
    {
        return $this->dlc;
    }

    public function getEffects()
    {
        return $this->effects;
    }

    public static function makeFromId($id)
    {
        Db::query("SELECT * FROM ingredients i LEFT JOIN ingredients_vanilla  v ON i.id=v.id WHERE i.id='{$id}'");

        return new static(Db::fetchAll());
    }

    public static function makeFromName($name)
    {
        Db::query("SELECT * FROM ingredients i LEFT JOIN ingredients_vanilla  v ON i.id=v.id WHERE i.name='{$name}'");

        return new static(Db::fetchAll());
    }
}