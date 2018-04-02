<?php namespace Skyrim;

use Msz\Db;
use Skyrim\Ingredient\EffectsList;

class Ingredient
{
    protected $id;
    protected $name;
    protected $nameru;
    protected $dlc;

    /** @var EffectsList */
    protected $effects;

    public function __construct(array $r)
    {
        if (sizeof($r) != 4) throw new \ErrorException('incorrect ingredient array');

        $this->id = $r[0]['id'];
        $this->name = $r[0]['name'];
        $this->nameru = $r[0]['nameru'];
        $this->dlc = $r[0]['dlc'];

        $this->effects = new EffectsList($r);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->nameru;
    }

    public function getDlc()
    {
        return $this->dlc;
    }

    /**
     * @return EffectsList
     */
    public function getEffects()
    {
        return $this->effects;
    }

    protected static function read($id, $mod)
    {
        $tbl1 = TBL_INGREDIENTS;
        $tbl2 = TBL_INGREDIENTS_EFFECTS;

        $q = "
          SELECT
            *
          FROM
            `{$tbl1}` i
            LEFT JOIN `{$tbl2}` v ON i.id=v.id AND v.dlc='{$mod}'
          WHERE
            i.id='{$id}'
          ORDER BY
            v.idx
        ";

        Db::query($q);

        if (!Db::numRows()) {
            throw new \Exception("no such ingredient with id `{$id}`");
        }

        $r = Db::fetchAll();
        Db::freeResult();
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

    public static function makeFromName($name, $mod)
    {
        if (is_null($mod)) $mod = Mod::getDefault();

        $tbl1 = TBL_INGREDIENTS;
        $tbl2 = TBL_INGREDIENTS_EFFECTS;

        $q = "
          SELECT
            *
          FROM
            `{$tbl1}` i
            LEFT JOIN `{$tbl2}` v ON i.id=v.id AND v.dlc='{$mod}'
          WHERE
            i.name='{$name}'
        ";

        Db::query($q);

        return new static(Db::fetchAll());
    }
}