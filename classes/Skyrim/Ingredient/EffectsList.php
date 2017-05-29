<?php
namespace Skyrim\Ingredient;

class EffectsList
{
    /** @var EffectStat[] */
    protected $data;

    public function __construct(array $r)
    {
        $this
        ->add($r[0]['effectId'], $r[0]['magnitude'], $r[0]['duration'])
        ->add($r[1]['effectId'], $r[1]['magnitude'], $r[1]['duration'])
        ->add($r[2]['effectId'], $r[2]['magnitude'], $r[2]['duration'])
        ->add($r[3]['effectId'], $r[3]['magnitude'], $r[3]['duration']);
    }

    public function add($id, $magnitude, $duration)
    {
        $this->data[$id] = new EffectStat(array(
            'id' => $id,
            'magnitude' => $magnitude,
            'duration' => $duration
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
}