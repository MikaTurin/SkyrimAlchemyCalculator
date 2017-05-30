<?php namespace Skyrim;

use Msz\Db;
use Msz\Forms\Control\Select;

class Mod
{
    public static $all;
    public static $mods;

    public static function getMods()
    {
        static::read();
        return static::$mods;
    }

    protected static function read()
    {
        if (!is_null(static::$all)) return;
        static::$all = Db::selectRowsByField(TBL_DLC, array('used' => 1));
        static::$mods = transform_array(static::$all, 'id', 'name');
    }

    public static function get()
    {
        $mod = static::getDefault();
        if (!empty($_REQUEST['mod']) && array_key_exists($_REQUEST['mod'], static::$mods)) {
            $mod = $_REQUEST['mod'];
        }

        return $mod;
    }

    public static function getPlayerClass($mod)
    {
        foreach (static::$all as $r) {
            if ($r['id'] != $mod) {
                continue;
            }
            return $r['playerClass'];
        }
        throw new \Exception('cant find player class');
    }

    /**
     * @return Select;
     */
    public static function getSelectObject()
    {
        return Select::make('mod')->loadArray(static::getMods())->setValue(static::get());
    }

    public static function getDefault()
    {
        static::read();
        return key(static::$mods);
    }

    public static function isVanilla()
    {
        return static::get() == 'VN';
    }

    public static function isPhitt()
    {
        return static::get() == 'PH';
    }

    public static function isRequiem()
    {
        return static::get() == 'RQ';
    }
}