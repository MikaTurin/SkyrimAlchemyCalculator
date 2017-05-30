<?php namespace Skyrim;

class Potion extends Structure
{
    protected $table = 'potions';

    protected $id;
    protected $dlc;
    protected $name;
    protected $weight;
    protected $price;
    protected $effectId;
    protected $magnitude;
    protected $duration;
}