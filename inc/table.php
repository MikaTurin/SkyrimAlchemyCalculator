<?php
class Table
{
    protected $data = array();
    protected $width = 0;
    protected static $style = 0;
    protected $skipFields = array();

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data)
    {
        return new static($data);
    }

    protected function drawRow(array $r)
    {
        $s = '<tr' . $this->getRowStyle($r) .'>';
        foreach ($r as $k => $v) {
            if (in_array($k, $this->skipFields)) continue;
            $s .= $this->drawRowTd($k, $v);
        }
        $s .= '</tr>';

        return $s;
    }

    protected function getRowStyle(array $r)
    {
        return '';
    }

    protected function drawRowTd($k, $v)
    {
        return '<td'.$this->getCellStyle($k, $v).'>' . $v . '</td>';
    }

    protected function getCellStyle($k, $v)
    {
        return '';
    }

    protected function drawHeader(array $r)
    {
        $s = '<tr>';
        foreach ($r as $k => $v) {
            if (in_array($k, $this->skipFields)) continue;
            $s .= $this->drawHeaderTh($k, $v);
        }
        $s .= '</tr>';

        return $s;
    }

    protected function drawHeaderTh($k, $v)
    {
        return '<th>' . $k . '</th>';
    }

    protected function getStyle()
    {
        if (static::$style) return '';

        static::$style = 1;
        return '<style>.vTable {border-collapse:collapse; } .vTable td, .vTable th { font-size:10pt; border:1px solid #ccc; font-family: Tahoma, Arial, sans-serif;}</style>';
    }

    public function html()
    {
        $r = $this->data;
        $c = sizeof($r);
        if (!$c) return '';

        $add = '';
        if ($this->width) $add = ' style="width:'.$this->width.'"';

        $s = $this->getStyle();
        $s .= '<table border="0" cellspacing="0" cellpadding="3" class="vTable"'.$add.'>';

        $s .= $this->drawHeader($r[0]);

        for ($i=0; $i<$c; $i++) {
            $s .= $this->drawRow($r[$i]);

        }
        $s .= '</table>';

        return $s;
    }

    public function draw()
    {
        echo $this->html();
    }
}