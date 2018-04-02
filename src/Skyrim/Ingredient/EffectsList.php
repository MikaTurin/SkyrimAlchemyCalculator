<?php
namespace Skyrim\Ingredient;

class EffectsList
{
    /** @var EffectStat[] */
    protected $data;

    public function __construct(array $r)
    {
        $this
        ->add($r[0]['effectId'], $r[0]['magnitude'], $r[0]['duration'], $r[0]['dlc'], $r[0]['idx'])
        ->add($r[1]['effectId'], $r[1]['magnitude'], $r[1]['duration'], $r[1]['dlc'], $r[1]['idx'])
        ->add($r[2]['effectId'], $r[2]['magnitude'], $r[2]['duration'], $r[2]['dlc'], $r[2]['idx'])
        ->add($r[3]['effectId'], $r[3]['magnitude'], $r[3]['duration'], $r[3]['dlc'], $r[3]['idx']);
    }

    public function add($id, $magnitude, $duration, $dlc, $idx)
    {
        $this->data[$id] = new EffectStat(array(
            'id' => $id,
            'magnitude' => $magnitude,
            'duration' => $duration,
            'dlc' => $dlc,
            'idx' => $idx
        ));

        return $this;
    }

    public function exists($id)
    {
        return array_key_exists($id, $this->data);
    }

    public function getIds()
    {
        return array_keys($this->data);
    }

    /**
     * @param $id
     * @return EffectStat
     * @throws \ErrorException
     */
    public function get($id)
    {
        if (!$this->exists($id)) {
            throw new \ErrorException('cant find effect id:'.$id);
        }

        return $this->data[$id];
    }

    public function getAll()
    {
        return $this->data;
    }

    public function __destruct()
    {
        unset($this->data);
    }
}