<?php namespace Skyrim\Player;

use Skyrim\Mod;

class PlayerFactory
{
    /**
     * @param $mod
     * @param array|null $data
     * @return PlayerVanilla
     * @throws \Exception
     */
    public static function create($mod, array $data = null)
    {
        $class = 'Skyrim\\Player\\' . Mod::getPlayerClass($mod);
        return new $class($data);
    }
}