<?php
namespace Skyrim;

use Msz\Db;

class Structure
{
    protected $table = '';

    public function __construct(array $r = null)
    {
        if ($r) {
            $this->load($r);
        }
    }

    public function load(array $r)
    {
        $vars = $this->getFields();

        if (is_array($r)) {
            foreach ($r as $k => $v) {
                if (array_key_exists($k, $vars)) {
                    $this->$k = $v;
                }
            }
        }
    }

    public function insert()
    {
        $q = '';
        $flds = $this->getFields();

        foreach ($flds as $fld => $val) {
            $val = Db::escape($val);
            $q .= "`{$fld}`='{$val}',";
        }
        $q = 'INSERT INTO `' . $this->table . '` SET ' . substr($q, 0, -1);
        dump($q);
        Db::query($q);
    }

    public function getFields()
    {
        $r = get_object_vars($this);
        unset($r['table']);
        return $r;
    }
}