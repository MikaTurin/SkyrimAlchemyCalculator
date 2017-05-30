<?php namespace Import;

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

    protected function getArrayFromText($s)
    {
        $info = trim($s);
        $info = str_replace("\r", '', $info);
        return explode("\n", $info);

    }

    protected function getMgefId($s)
    {
        return preg_replace('/^.*\[MGEF\:([A-Z0-9]{8})\]\s?$/iU', '$1', $s);
    }

}