<?php namespace Tes5Edit;

abstract class Import
{
    protected $mod;

    public function __construct($mod)
    {
        $this->mod = $mod;
    }

    abstract public function process($info);

    protected function fixDlcFormId($id)
    {
        if (substr($id, 0, 2) != '00') {
            $id = 'xx' . substr($id, 2);
        }

        return $id;
    }

}