<?php
namespace Skyrim;

class Structure
{
    public function __construct(array $r = null)
    {
        $vars = get_object_vars($this);

        if (is_array($r)) {
            foreach ($r as $k => $v) {
                if (array_key_exists($k, $vars)) {
                    $this->$k = $v;
                }
            }
        }
    }
}