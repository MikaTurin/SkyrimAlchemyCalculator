<?php
use Msz\Db;
use Msz\Forms\Control\Select;

class Mod
{
    public static $mods = null;

    public static function getMods()
    {
        static::read();
        return static::$mods;
    }

    protected static function read()
    {
        if (!is_null(static::$mods)) return;
        static::$mods = Db::selectRowsByField(TBL_DLC, array('used' => 1));
        static::$mods = transform_array(static::$mods, 'id', 'name');
    }

    public static function get()
    {
        $mod = static::getDefault();
        if (!empty($_REQUEST['mod']) && array_key_exists($_REQUEST['mod'], static::$mods)) $mod = $_REQUEST['mod'];

        return $mod;
    }

    /**
     * @return Msz\Forms\Control\Base;
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