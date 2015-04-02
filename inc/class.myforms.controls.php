<?php
//-------------------------------------------------------------------
class myform_control
{
    var $form_name;
    var $name;
    var $class;
    var $type;
    var $value;
    var $key;
    var $tag_extra = '';
    var $draw_value = false;
    var $save_empty = true;
    var $preg_check = '';
    var $label;
    var $strip_tags = true;

    function myform_control($form_name, $name, $value = null)
    {
        $this->form_name = $form_name;
        $this->name = $name;
        if (isset ($value)) {
            $this->value = $value;
        }
        $this->key = $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    function rename($name)
    {
        $this->name = $name;
        $this->key = $name;
    }

    function html()
    {
        return '';
    }

    function destroy()
    {
    }

    function draw()
    {
        echo $this->html();
    }

    function process()
    {
        if (isset ($_POST[$this->key])) {
            $tmp = $_POST[$this->key];
            $tmp = stripslashes($tmp);
            if ($this->strip_tags) {
                $tmp = preg_replace('/<.*>/isU', '', $tmp);
            }
            $tmp = trim($tmp);
            $this->value = $tmp;
        }
    }

    function generate_extra()
    {
        if (strlen($this->tag_extra)) {
            return ' ' . $this->tag_extra;
        }
        return '';
    }

    function html_hidden()
    {
        return '<input type="hidden" name="' . $this->key . '" value="' . $this->value . '">';
    }
}
//-------------------------------------------------------------------
class myform_hidden extends myform_control
{
  var $constant; // if true value can be added only from class constructor

  function myform_hidden ($form_name, $name, $value = NULL, $constant = false)
  {
    $this->myform_control ($form_name, $name, $value);
    $this->constant = $constant;
  }

  function html ()
  {
    return '<input type="hidden" name="'.$this->key.'" value="'.htmlspecialchars ($this->value).'">';
  }

  function process ()
  {
    if (!$this->constant && isset ($_POST[$this->key])) {$this->value = stripslashes ($_POST[$this->key]);}
  }
}
//-------------------------------------------------------------------
class myform_label extends  myform_control
{
  function myform_label ($form_name, $name, $class = '')
  {
    $this->myform_control($form_name, $name);
    $this->class = $class;
  }

  function html ()
  {
    return '<div class='.$this->class.'>'.$this->value.'</div>';
  }
}
//-------------------------------------------------------------------
class myform_textbox extends myform_control
{
  var $size;
  var $maxlength = 255;
  var $isPassword = false;

  function myform_textbox ($form_name, $name, $class = '', $size = 0, $maxlength = 0)
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;
    $this->size = $size;
    if ($maxlength > 0) $this->maxlength = $maxlength;
  }

  function html ()
  {
    if ($this->draw_value)
    {
      $this->value = trim ($this->value);
      $this->value = htmlspecialchars ($this->value);
      if (!strlen ($this->value)) {$this->value = '&nbsp;';}
      return $this->value;
    }

    $extra = $this->generate_extra ();

    $class = $size = '';
    if (strlen ($this->class)) $class = ' class="'.$this->class.'"';
    if ($this->size) $size = ' size="'.$this->size.'"';
    $value = '';
    if (isset ($this->value)) $value = $this->value;

    $type = 'text';
    if ($this->isPassword) $type = 'password';
    return '<input type="'.$type.'" name="'.$this->key.'" value="'.htmlspecialchars ($value).'"'.$class.' maxlength="'.$this->maxlength.'"'.$size.$extra.'>';
  }
}
//-------------------------------------------------------------------
class myform_digit extends myform_textbox
{
  var $simple;
  var $useIntval = true;

  function myform_digit ($form_name, $name, $class = '', $size = 0, $maxlength = 0)
  {
    $this->myform_textbox ($form_name, $name, $class, $size, $maxlength);
    $this->simple = false;
  }

  function html ()
  {
    $tp = 2;
    if ($this->simple) $tp = 1;
    $this->tag_extra .= ' onchange="to_digit(this,'.$tp.');"';
    $this->tag_extra = trim ($this->tag_extra);
    return myform_textbox::html ();
  }

  function process ()
  {
    if (isset ($_POST[$this->key]))
    {
      $tmp = $_POST[$this->key];
      $tmp = stripslashes ($tmp);
      $tmp = preg_replace ('/<.*>/isU', '', $tmp);
      $tmp = trim ($tmp);
      $tmp = str_replace (',', '.', $tmp);
      if ($this->simple)
      {
        $pos = strpos ($tmp, '.');
        if (!($pos === false)) {$tmp = substr ($tmp, 0, $pos - 1);}
        if ($this->useIntval) $tmp = intval ($tmp);
      }

      $this->value = $tmp;
    }
  }
}
//-------------------------------------------------------------------
class myform_textarea extends myform_control
{
  var $cols;
  var $rows;

  function myform_textarea ($form_name, $name, $class = '', $cols = 40, $rows = 5)
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;
    $this->cols = $cols;
    $this->rows = $rows;
  }

  function html ()
  {
    if ($this->draw_value) {return nl2br (htmlspecialchars ($this->value));}

    $class = '';
    $extra = $this->generate_extra ();
    if (strlen ($this->class)) {$class = ' class="'.$this->class.'"';}
    $value = '';
    if (isset ($this->value)) {$value = $this->value;}

    return '<textarea name="'.$this->key.'"'.$class.' cols="'.$this->cols.'" rows="'.$this->rows.'"'.$extra.'>'.htmlspecialchars ($value).'</textarea>';
  }
}
//-------------------------------------------------------------------
class myform_radiobox extends myform_control
{
    var $values;
    var $captions;
    var $empty; // if true no boxes are selected, if false the first one is
    var $cellspacing = 0;
    var $cellpadding = 0;

    function myform_radiobox($form_name, $name, $values = false, $captions = false, $empty = false)
    {
        $this->myform_control($form_name, $name);
        $this->values = $values;
        $this->captions = $captions;
        $this->empty = $empty;
        $this->value = false;
    }

    function html($idx = false, $table = false)
    {
        $ret = '';
        if (!is_array($this->values)) {
            $this->values = array();
        }
        $cnt = sizeof($this->values);
        $extra = $this->generate_extra();
        $show_caption = is_array($this->captions);

        if ($this->draw_value) {
            if ($show_caption) {
                for ($i = 0; $i < $cnt; $i++) {
                    if ($this->value !== false && $this->value == $this->values[$i]) {
                        return $this->captions[$i];
                    }
                }
            }
            return '';
        }

        if (!$this->empty && !isset ($this->value)) {
            $this->value = $this->values[0];
        }

        if ($idx === false) {
            if ($table) {
                $ret .= '<table cellspacing="' . $this->cellspacing . '" cellpadding="' . $this->cellpadding . '" border="0">';
            }
            for ($i = 0; $i < $cnt; $i++) {
                $checked = ($this->value !== false && $this->values[$i] == $this->value) ? ' checked' : '';
                $caption = $this->values[$i];
                if ($show_caption) {
                    $caption = $this->captions[$i];
                }
                if (!$table && $show_caption) {
                    $caption = '&nbsp;' . $caption;
                }

                if (!$table) {
                    $ret .= '<input type="radio" name="' . $this->key . '" value="' . $this->values[$i] . '" id="l' . $this->key . $i . '"' . $checked . $extra . '><label for="l' . $this->key . $i . '">' . $caption . '</label><br>';
                } else {
                    $ret .= '<tr><td valign="top"><input type="radio" name="' . $this->key . '" id="l' . $this->key . $i . '" value="' . $this->values[$i] . '"' . $checked . $extra . '></td><td>&nbsp;</td><td><label for="l' . $this->key . $i . '">' . $caption . '</label></td></tr>';
                }
            }
            if ($table) {
                $ret .= '</table>';
            }
        } else {
            $caption = '';
            if ($show_caption) {
                $caption = $this->captions[$idx];
            }
            if (strlen($caption)) {
                $caption = '<label for="l' . $this->key . $idx . '">' . $caption . '</label>';
            }
            if (!$table && $show_caption) {
                $caption = '&nbsp;' . $caption;
            }

            $checked = ($this->value !== false && $this->values[$idx] == $this->value) ? ' checked' : '';
            if (!$table) {
                $ret .= '<input type="radio" name="' . $this->key . '" value="' . $this->values[$idx] . '" id="l' . $this->key . $idx . '"' . $checked . $extra . '>' . $caption;
            } else {
                $ret .=
                    '<table cellspacing="0" cellpadding="0" border="0">' .
                    '<tr><td valign="top"><input type="radio" name="' . $this->key . '" value="' . $this->values[$idx] . '" id="l' . $this->key . $idx . '"' . $checked . $extra . '></td><td>&nbsp;</td><td>' . $caption . '</td></tr>' .
                    '</table>';
            }
        }

        return $ret;
    }

    function loadArray($r)
    {
        if (!is_array($r)) {
            return false;
        }
        foreach ($r as $k => $v) {
            $this->values[] = $k;
            $this->captions[] = $v;
        }
        return $this;
    }

    function draw($idx = false, $table = false)
    {
        echo $this->html($idx, $table);
    }
}
//-------------------------------------------------------------------
class myform_checkbox extends myform_control
{
  var $checked_value;
  var $unchecked_value;
  var $caption;
  var $cellspacing = 0;
  var $cellpadding = 0;

  function myform_checkbox ($form_name, $name, $caption = '', $checked_value = 1, $unchecked_value = 0)
  {
    $this->myform_control ($form_name, $name);
    $this->checked_value = $checked_value;
    $this->unchecked_value = $unchecked_value;
    $this->caption = $caption;
  }

  function html ($table = false)
  {
    if ($this->draw_value)
    {
      if ($this->value == $this->checked_value) {return $this->caption;}
      return '';
    }

    $extra = $this->generate_extra ();
    $caption = $checked = '';
    $caption = trim ($this->caption);
    if (strlen ($caption)) {$caption = '<label for="r'.$this->key.'">'.$caption.'</label>';}
    if (strlen (trim ($this->caption)) && !$table) {$caption = '&nbsp;'.$caption;}
    if (isset ($this->value))
    {
      $checked = ($this->value == $this->checked_value) ? ' checked' : '';
    }

    if ($table)
    {
      return
      '<table cellspacing="'.$this->cellspacing.'" cellpadding="'.$this->cellpadding.'" border="0">'.
        '<tr>'.
          '<td valign="top"><input type="checkbox" name="'.$this->key.'" id="r'.$this->key.'"'.$checked.$extra.'></td>'.
          '<td>&nbsp;</td>'.
          '<td>'.$caption.'</td>'.
      '</table>';
    }
    else
    {
      return '<input type="checkbox" name="'.$this->key.'" id="r'.$this->key.'"'.$checked.$extra.'>'.$caption;
    }

  }

  function draw ($table = false)
  {
    echo $this->html ($table);
  }

  function process ()
  {
    $this->value = $this->unchecked_value;
    if (isset ($_POST[$this->key])) {$this->value = $this->checked_value;}
  }
}
//-------------------------------------------------------------------
class myform_checkbox_list extends myform_control
{
  var $checked_value;
  var $unchecked_value;
  var $objects;
  var $cellspacing = 0;
  var $cellpadding = 0;

  function myform_checkbox_list ($form_name, $name, $captions = false, $checked_value = 1, $unchecked_value = 0)
  {
    $this->myform_control ($form_name, $name);
    $this->checked_value = $checked_value;
    $this->unchecked_value = $unchecked_value;

    $cnt = sizeof ($captions);
    $this->objects = array ();

    for ($i=0; $i<$cnt; $i++)
    {
      $this->objects[] = new myform_checkbox ($form_name, $name.'_'.$i, $captions[$i], $checked_value, $unchecked_value);
    }
  }

  function html ($idx = false, $table = false)
  {
    $ret = '';
    $cnt = sizeof ($this->objects);

    if (isset ($this->value))
    {
      for($i=0;$i<$cnt;$i++)
      {
        if (isset ($this->value[$i])) {$this->objects[$i]->value = $this->value[$i];}
      }
    }

    if ($idx === false)
    {
      for ($i=0; $i<$cnt; $i++)
      {
        if (!strlen ($this->objects[$i]->tag_extra)) {$this->objects[$i]->tag_extra = $this->tag_extra;}
        $this->objects[$i]->draw_value = $this->draw_value;
              $this->objects[$i]->cellspacing = $this->cellspacing;
              $this->objects[$i]->cellpadding = $this->cellpadding;
        $tmp = $this->objects[$i]->html ($table);
        $ret .= $tmp.' ';
        if (!$table && strlen ($tmp) && $i + 1 < $cnt) $ret .= '<br>';
        if ($table && $this->draw_value) $ret .= '<br>';
      }
    }
    else
    {
      if (!strlen ($this->objects[$idx]->tag_extra)) {$this->objects[$idx]->tag_extra = $this->tag_extra;}
      $this->objects[$idx]->draw_value = $this->draw_value;
      $ret .= $this->objects[$idx]->html ($table).' ';
    }

    return trim ($ret);
  }

  function draw ($idx = false, $table = false)
  {
    echo $this->html ($idx, $table);
  }

  function process ()
  {
    $cnt = sizeof ($this->objects);
    $this->value = '';

    for ($i=0; $i<$cnt; $i++)
    {
      if (isset ($_POST[$this->objects[$i]->key]))
      {
        $this->objects[$i]->value = $this->checked_value;
        $this->value .= $this->checked_value;
      }
      else
      {
        $this->objects[$i]->value = $this->unchecked_value;
        $this->value .= $this->unchecked_value;
      }
    }
  }
}
//-------------------------------------------------------------------
class myform_combobox extends myform_control
{
    var $captions;
    var $values;
    var $optionsExtra;

    function myform_combobox($form_name, $name, $values = false, $captions = false, $class = '')
    {
        $this->myform_control($form_name, $name);
        if (!is_array($captions)) {
            $captions = array();
        }
        if (!is_array($values)) {
            $values = array();
        }
        $this->captions = $captions;
        $this->values = $values;
        $this->class = $class;
        $this->optionsExtra = array();
    }

    function loadArray($r, $empty_array = true)
    {
        if (!is_array($r)) {
            return false;
        }
        if ($empty_array) {
            $this->values = $this->captions = array();
        }
        foreach ($r as $k => $v) {
            $this->values[] = $k;
            $this->captions[] = $v;
        }
        return $this;
    }

    function html()
    {
        if ($this->draw_value) {
            $cnt = sizeof($this->values);
            for ($i = 0; $i < $cnt; $i++) {
                if ((string)$this->value == (string)$this->values[$i]) {
                    return $this->captions[$i];
                }
            }
            return '';
        }

        $class = '';
        $extra = $this->generate_extra();
        if (strlen($this->class)) {
            $class = ' class="' . $this->class . '"';
        }

        $ret = '<select name="' . $this->key . '"' . $class . $extra . '>';

        $cnt = sizeof($this->values);
        for ($i = 0; $i < $cnt; $i++) {
            $selected = $extra = '';
            if ($this->value == $this->values[$i]) {
                $selected = ' selected';
            }
            if (isset ($this->optionsExtra[$this->values[$i]])) {
                $extra = ' ' . $this->optionsExtra[$this->values[$i]];
            }
            $ret .= '<option value="' . $this->values[$i] . '"' . $selected . $extra . '>' . $this->captions[$i] . '</option>';
        }

        $ret .= '</select>';
        return $ret;
    }
}
//-------------------------------------------------------------------
class myform_date extends myform_control
{
  var $dd;
  var $mm;
  var $yy;

  function myform_date ($form_name, $name, $class = '', $size = '')
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;

    $size_arr = array (false, false, false);
    if (!strlen ($size)) {$size = '1,1,3';}
    $size_arr = explode (',', $size);

    $this->dd = new myform_textbox ($form_name, $name.'_dd', $class, $size_arr[0], 2);
    $this->mm = new myform_textbox ($form_name, $name.'_mm', $class, $size_arr[1], 2);
    $this->yy = new myform_textbox ($form_name, $name.'_yy', $class, $size_arr[2], 4);
  }

  function get_empty_date ()
  {
    return array ('dd'=>'', 'mm'=>'', 'yy'=>'', 'hh'=>'', 'ii'=>'', 'ss'=>'');
  }

  function now()
  {
    $this->value = date('Y-m-d H:i:s', time());
  }

  function explode_date ()
  {
    $r = $this->get_empty_date ();

    if (!isset ($this->value)) return $r;
    if (!preg_match ('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/isU', $this->value)) $this->now ();
    if ($this->value == '0000-00-00' || $this->value == '0000-00-00 00:00:00') $this->now ();

    $r['yy'] = substr ($this->value, 0, 4);
    $r['mm'] = $this->add_zero (substr ($this->value, 5, 2));
    $r['dd'] = $this->add_zero (substr ($this->value, 8, 2));
    $r['hh'] = $this->add_zero (substr ($this->value, 11, 2));
    $r['ii'] = $this->add_zero (substr ($this->value, 14, 2));
    $r['ss'] = $this->add_zero (substr ($this->value, 17, 2));

    return $r;
  }

  function add_zero ($val)
  {
    if (strlen ($val) == 1) {$val = '0'.$val;}
    return $val;
  }

  function get_month_days ($mm, $yy)
  {
    for ($i=31; $i>=28; $i--) {if (checkdate ($mm, $i, $yy)) {return $i;}}
    return 28;
  }

  function html ()
  {
    $date = $this->explode_date ();
    $this->dd->value = $date['dd'];
    $this->mm->value = $date['mm'];
    $this->yy->value = $date['yy'];

    if ($this->draw_value)
    {
      return $this->add_zero ($date['dd']).'.'.$this->add_zero ($date['mm']).'.'.$date['yy'];
    }

    if ($this->yy->value == '0000') {$this->yy->value = '';}
    if ($this->mm->value == '00') {$this->mm->value = '';}
    if ($this->dd->value == '00') {$this->dd->value = '';}

    return $this->dd->html ().$this->mm->html ().$this->yy->html ();
  }

  function process ()
  {
    if (isset ($_POST[$this->key.'_dd']) && isset ($_POST[$this->key.'_mm']) && isset ($_POST[$this->key.'_yy']))
    {
      $dd = intval ($_POST[$this->key.'_dd']);
      $mm = intval ($_POST[$this->key.'_mm']);
      $yy = intval ($_POST[$this->key.'_yy']);

      $hh = $ii = $ss = 0;
      if (isset ($_POST[$this->key.'_hh'])) $hh = intval ($_POST[$this->key.'_hh']);
      if (isset ($_POST[$this->key.'_ii'])) $ii = intval ($_POST[$this->key.'_ii']);
      if (isset ($_POST[$this->key.'_ss'])) $ss = intval ($_POST[$this->key.'_ss']);

      $tmp = mktime ($hh, $ii, $ss, $mm, $dd, $yy);
      if ($tmp < 0) $tmp = 0;
      $this->value = date ('Y-m-d H:i:s', $tmp);
    }
  }
}
//-------------------------------------------------------------------
class myform_special_date extends myform_date
{
  function myform_special_date ($form_name, $name, $month_arr = false, $class = '', $yclass = '')
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;

    if (!(is_array ($month_arr) && sizeof ($month_arr) == 12)) $month_arr = array ('01','02','03','04','05','06','07','08','09','10','11','12');

    $month_values = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

    $this->dd = new myform_combobox ($form_name, $name.'_dd', false, false, $class);
    $this->mm = new myform_combobox ($form_name, $name.'_mm', $month_values, $month_arr, $class);
    $this->yy = new myform_textbox ($form_name, $name.'_yy', $yclass, 0, 4);

    $this->mm->tag_extra = 'onchange="myforms_specialdate_change(this.form.'.$this->dd->key.',this,this.form.'.$this->yy->key.');"';
    $this->yy->tag_extra = 'onblur="myforms_specialdate_change(this.form.'.$this->dd->key.',this.form.'.$this->mm->key.',this);"';
  }

  function html ()
  {
    if (preg_match ('/^0000\-00\-00/isU', $this->value)) $this->now ();
    $date = $this->explode_date ();

    $this->yy->value = $date['yy'];
    $this->mm->value = $date['mm'];
    $this->dd->value = $date['dd'];

    if ($this->draw_value)
    {
      return $this->add_zero ($date['dd']).'.'.$this->add_zero ($date['mm']).'.'.$date['yy'];
    }

    $cnt = $this->get_month_days ($date['mm'], $date['yy']);
    $dd_values = $dd_captions = array ();

    for ($i=1; $i<=$cnt; $i++)
    {
      $dd_values[] = $i;
      $dd_captions[] = $this->add_zero ($i);
    }

    $this->dd->captions = $dd_captions;
    $this->dd->values = $dd_values;

    $ret = $this->dd->html ().$this->mm->html ().$this->yy->html ();


    return $ret;
  }
}
//-------------------------------------------------------------------
class myform_full_date extends myform_date
{
  var $hh;
  var $ii;
  var $ss;
  var $use_seconds;

  function myform_full_date ($form_name, $name, $month_arr = false, $class = '', $yclass = '')
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;

    if (!(is_array ($month_arr) && sizeof ($month_arr) == 12)) $month_arr = array ('01','02','03','04','05','06','07','08','09','10','11','12');

    $month_values = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

    $this->dd = new myform_combobox ($form_name, $name.'_dd', false, false, $class);
    $this->mm = new myform_combobox ($form_name, $name.'_mm', $month_values, $month_arr, $class);
    $this->yy = new myform_textbox ($form_name, $name.'_yy', $yclass, 0, 4);

    $this->mm->tag_extra = 'onchange="myforms_specialdate_change(this.form.'.$this->dd->key.',this,this.form.'.$this->yy->key.');"';
    $this->yy->tag_extra = 'onblur="myforms_specialdate_change(this.form.'.$this->dd->key.',this.form.'.$this->mm->key.',this);"';

          $hr = array ();
          for ($i=0; $i<24; $i++) $hr[] = $this->add_zero ($i);
          $mr = array ();
          for ($i=0; $i<60; $i++) $mr[] = $this->add_zero ($i);

          //$this->use_seconds = false;

    $this->hh = new myform_combobox ($form_name, $name.'_hh', $hr, $hr, $class);
    $this->ii = new myform_combobox ($form_name, $name.'_ii', $mr, $mr, $class);
    //if ($this->use_ss) $this->ss = new myform_combobox ($form_name, $name.'_ss', $mr, $mr, $class);
  }

  function html ()
  {
    $date = $this->explode_date ();
    $this->yy->value = $date['yy'];
    $this->mm->value = $date['mm'];
    $this->dd->value = $date['dd'];
    $this->hh->value = $date['hh'];
    $this->ii->value = $date['ii'];

    if ($this->draw_value)
    {
      return $this->add_zero ($date['dd']).'.'.$this->add_zero ($date['mm']).'.'.$date['yy'].' '.$this->add_zero ($date['ss']).':'.$this->add_zero ($date['ii']);
    }

    $cnt = $this->get_month_days ($date['mm'], $date['yy']);
    $dd_values = $dd_captions = array ();

    for ($i=1; $i<=$cnt; $i++)
    {
      $dd_values[] = $i;
      $dd_captions[] = $this->add_zero ($i);
    }

    $this->dd->captions = $dd_captions;
    $this->dd->values = $dd_values;

    $ret = $this->dd->html ().$this->mm->html ().$this->yy->html ().$this->hh->html().':'.$this->ii->html();


    return $ret;
  }
}
//-------------------------------------------------------------------
class myform_combo_time extends myform_control
{
  var $hh;
  var $mm;
  var $ss;
  var $use_ss;

  function myform_combo_time ($form_name, $name, $class = '', $use_ss = true)
  {
    $this->myform_control ($form_name, $name);
    $hr = array ();
    for ($i=0; $i<24; $i++) $hr[] = $this->add_zero ($i);
    $mr = array ();
    for ($i=0; $i<60; $i++) $mr[] = $this->add_zero ($i);

    $this->class = $class;
    $this->use_ss = $use_ss;

    $this->hh = new myform_combobox ($form_name, $name.'_hh', $hr, $hr, $class);
    $this->mm = new myform_combobox ($form_name, $name.'_mm', $mr, $mr, $class);
    if ($this->use_ss) $this->ss = new myform_combobox ($form_name, $name.'_ss', $mr, $mr, $class);
  }

  function add_zero ($val)
  {
    if (strlen ($val) == 1) {$val = '0'.$val;}
    return $val;
  }

  function html()
  {
    $r = $this->parse_value();
    if ($this->draw_value)
    {
      $tmp = $r['hh'].':'.$r['mm'];
      if ($this->use_ss) $tmp .= $r['ss'];
      return $tmp;
    }
    $this->hh->value = $r['hh'];
    $this->mm->value = $r['mm'];
    if ($this->use_ss) $this->ss->value = $r['ss'];

    $ret = $this->hh->html().':'.$this->mm->html();
    if ($this->use_ss) $ret .= ':'.$this->ss->html();
    return $ret;
  }

  function now()
  {
    $this->value = date('H:i:s', time());
  }

  function parse_value()
  {
    $tmp = array ('hh'=>'', 'mm'=>'', 'ss'=>'');
    $r = explode (':', $this->value);
    $cnt = sizeof ($r);

    if ($cnt) $tmp['hh'] = $r[0];
    if ($cnt>1) $tmp['mm'] = $r[1];
    if ($cnt>2) $tmp['ss'] = $r[2];
    return $tmp;
  }

  function process ()
  {
    $val = '00:00';
    if ($this->use_ss) $val .= ':00';

    if (isset ($_POST[$this->key.'_hh']))
    {
      $tmp = $_POST[$this->key.'_hh'];
      $tmp = intval (trim ($tmp));
      $hh = $this->add_zero($tmp);
      $val = $hh.substr ($val, 2);
    }
    if (isset ($_POST[$this->key.'_mm']))
    {
      $tmp = $_POST[$this->key.'_mm'];
      $tmp = intval (trim ($tmp));
      $mm = $this->add_zero($tmp);
      $val = substr ($val, 0, 3).$mm.substr ($val,6);
    }
    if ($this->use_ss && isset ($_POST[$this->key.'_ss']))
    {
      $tmp = $_POST[$this->key.'_ss'];
      $tmp = intval (trim ($tmp));
      $ss = $this->add_zero($tmp);
      $val = substr ($val, 0, 6).$ss;
    }
    $this->value = $val;
  }
}
//-------------------------------------------------------------------
class myform_combo_date extends myform_date
{
  function myform_combo_date ($form_name, $name, $year_arr, $month_arr = false, $class = '')
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;

    if (!(is_array ($month_arr) && sizeof ($month_arr) == 12))
    {
      $month_arr = array ('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    }

    $month_values = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

    $this->dd = new myform_combobox ($form_name, $name.'_dd', false, false, $class);
    $this->mm = new myform_combobox ($form_name, $name.'_mm', $month_values, $month_arr, $class);
    $this->yy = new myform_combobox ($form_name, $name.'_yy', $year_arr, $year_arr, $class);

    $this->mm->tag_extra = 'onchange="myforms_combodate_change(this.form.'.$this->dd->key.',this,this.form.'.$this->yy->key.');"';
    $this->yy->tag_extra = 'onchange="myforms_combodate_change(this.form.'.$this->dd->key.',this.form.'.$this->mm->key.',this);"';
  }

  function get_empty_date ()
  {
    return array ('yy' => max($this->yy->values), 'mm' => 1, 'dd' => 1);
  }

  function html ()
  {
    $date = $this->explode_date ();
    $this->yy->value = $date['yy'];
    $this->mm->value = $date['mm'];
    $this->dd->value = $date['dd'];

    if ($this->draw_value)
    {
      return $this->add_zero ($date['dd']).'.'.$this->add_zero ($date['mm']).'.'.$date['yy'];
    }

    $cnt = $this->get_month_days ($date['mm'], $date['yy']);
    $dd_values = $dd_captions = array ();

    for ($i=1; $i<=$cnt; $i++)
    {
      $dd_values[] = $i;
      $dd_captions[] = $this->add_zero ($i);
    }

    $this->dd->captions = $dd_captions;
    $this->dd->values = $dd_values;

    $ret = $this->dd->html ().$this->mm->html ().$this->yy->html ();


    return $ret;
  }
}
//-------------------------------------------------------------------
class myform_file extends myform_control
{
  var $path;
  var $filename;
  var $options;

  function myform_file ($form_name, $name, $path = './', $filename = '', $class = '', $size = 0)
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;
    $this->path = $path;
    $this->filename = $filename;
    $this->size = $size;
    $this->save_empty = false;
  }

  function html ()
  {
    if ($this->draw_value) {return htmlspecialchars ($this->value);}

    $extra = $this->generate_extra ();

    $class = $size = '';
    if (strlen ($this->class)) {$class = ' class="'.$this->class.'"';}
    if ($this->size) {$size = ' size="'.$this->size.'"';}
    $value = '';
    if (isset ($this->value)) {$value = $this->value;}

    return '<input type="file" name="'.$this->key.'"'.$class.$size.$extra.'>';
  }

  function create_unique_filename ($val)
  {
    $file = $val;
    $ext = '';

    $pos = strpos ($val, '.');
    if (!($pos === false))
    {
      $file = substr ($val, 0, $pos);
      $ext = substr ($val, $pos + 1);
    }

    if (!preg_match('/^.*\([0-9]+\)$/isU', $file))
    {
      $file .= '(1)';
    }
    else
    {
      $file = substr ($file, 0, -1);
      $pos = strrpos ($file, '(');
      $num = substr ($file, $pos + 1);
      $num = intval ($num);
      $num ++;
      $file = substr ($file, 0, $pos + 1).$num.')';
    }

    if (strlen ($ext)) {$file .= '.'.$ext;}
    return $file;
  }

  function get_unique_filename ($val)
  {
    if (file_exists ($this->path.$val))
    {
      $val = $this->get_unique_filename ($this->create_unique_filename ($val));
    }
    return $val;
  }

  function process ()
  {
    if (isset ($_FILES[$this->key]))
    {
      $this->options = $_FILES[$this->key];
      if (strlen ($this->options['tmp_name']))
      {
        $name = $this->filename;
        if (!strlen ($name))
        {
          $tmp = $this->options['name'];
          $tmp = str_replace (' ', '_', $tmp);
          $tmp = str_replace ('+', '_', $tmp);
          $pos = strrpos ($tmp, '.');
          $ext = '';
          if ($pos !== false && $pos + 1 != strlen ($tmp)) $ext = substr ($tmp, $pos + 1);
          if (strlen ($ext)) $tmp = preg_replace ('/\.'.$ext.'$/isU', '.'.strtolower ($ext), $tmp);

          $tmp = trim ($tmp);
          $name = $this->get_unique_filename ($tmp);
        }
        move_uploaded_file ($this->options['tmp_name'], $this->path.$name);
        $this->value = $name;
      }
    }
  }
}
//-------------------------------------------------------------------
class myform_picture_select extends myform_textbox
{
  var $dialog;
  var $btn_class1 = 'spBtn1';
  var $btn_class2 = 'spBtn2';

  function myform_picture_select ($form_name, $name, $dialog = 'pictures', $btn_class = 'spBtn1', $class = '')
  {
    $this->myform_textbox ($form_name, $name, $class, false, 255);
    $this->tag_extra = 'readonly';
    $this->dialog = $dialog;
  }

  function html ()
  {
    $text = myform_textbox::html ();
    $text .= '&nbsp;';
    $text .= '<button class="'.$this->btn_class1.'" onclick="'.$this->dialog.'(this.form.'.$this->key.');this.blur();"></button>';
    $text .= '&nbsp;';
    $text .= '<button class="'.$this->btn_class2.'" onclick="this.form.'.$this->key.'.value=\'\';this.blur();"></button>';
    return $text;
  }
}
//-------------------------------------------------------------------
class myform_document_select extends myform_control
{
  var $txtName;
  var $txtId;
  var $dialog = 'structure';
  var $dialogType = 0;
  var $dialogParent = false;
  var $dialogModule = false;
  var $btn1Class = 'spBtn1';
  var $jsBefore = '';
  var $jsAfter = '';
  var $btn2Class = 'spBtn2';
  var $dialogTitle;
  var $nameLoadFunc = 'get_name';

  function myform_document_select ($form_name, $name, $dialog = '', $classTxt = '', $classId = '', $btn1_class = '', $btn2_class = '')
  {
    $this->myform_control ($form_name, $name);
    $this->txtName = new myform_textbox ($form_name, $name.'_name', $classTxt, 0, 128);
    $this->txtId = new myform_textbox ($form_name, $name, $classId, 0, 128);
    $this->txtId->tag_extra = 'readonly';

    if (strlen ($dialog)) $this->dialog = $dialog;
    if (strlen ($btn1_class)) $this->btn1Class = $btn1_class;
    if (strlen ($btn2_class)) $this->btn2Class = $btn2_class;
    $this->dialogTitle = '';
  }

  function html ()
  {
    $cookie = $cookie2 = '';
    if ($this->dialogParent !== false)
    {
      $cookie .= 'set_cookie (\'tmpParent\', '.$this->dialogParent.');';
      $cookie2 .= 'set_cookie (\'tmpParent\',\'\');';
    }
    if (defined('CURRENT_MODULE')) $cc = 'var curModule=\''.CURRENT_MODULE.'\';'; else $cc = 'var curModule=\'1\';';
    if ($this->dialogModule)
    {
      $cookie .= $cc.'set_cookie (\'current_module\', '.$this->dialogModule.');';
      $cookie2 = 'set_cookie (\'current_module\', curModule);';
    }
    $cookie .= $this->jsBefore;
    $cookie2 .= $this->jsAfter;

    $this->txtName->tag_extra = 'readonly onclick="'.$cookie.'var r=dialog_show(\''.$this->dialog.'\',\''.$this->dialogTitle.'\','.$this->dialogType.');'.$cookie2.'if(r){this.form.'.$this->txtId->key.'.value=r[\'id\'];this.value=r[\'name\'];}" style="cursor:hand;"';
    if ($this->value)
    {
      $this->txtId->value = $this->value;
      if (strlen ($this->nameLoadFunc) && function_exists($this->nameLoadFunc))
      {
        $tmp = $this->nameLoadFunc;
        $this->txtName->value = $tmp($this->value);
      }
    }

    $btn =
    '&nbsp;<button class="'.$this->btn1Class.'" onclick="'.$cookie.'var r=dialog_show(\''.$this->dialog.'\',\''.$this->dialogTitle.'\','.$this->dialogType.');if(r){this.form.'.$this->txtId->key.'.value=r[\'id\'];this.form.'.$this->txtName->key.'.value=r[\'name\'];}this.blur();"></button>'.
    '&nbsp;<button class="'.$this->btn2Class.'" onclick="this.form.'.$this->txtName->key.'.value=\'\';this.form.'.$this->txtId->key.'.value=\'0\';this.blur();"></button>';

    $txt =
    '<table cellspacing=0 cellpadding=0 border=0 width=100%>'.
      '<tr>'.
        '<td width=100%>'.$this->txtName->html().'</td>'.
        '<td>'.$this->txtId->html().'</td>'.
        '<td>'.$btn.'</td>'.
      '</tr>'.
    '</table>';

    return $txt;
  }

  function setValue ($id, $name)
  {
    $this->txtId->value = $id;
    $this->txtName->value = $name;
  }

  function process ()
  {
    $this->txtId->process();
    $this->txtName->process();
    $this->value = $this->txtId->value;
  }
}
//-------------------------------------------------------------------
class myform_select_date extends myform_date
{
  var $buttonClass;

  function myform_select_date ($form_name, $name, $button_class, $class = '')
  {
    $this->buttonClass = $button_class;
    $this->myform_date ($form_name, $name, $class);
    $this->dd->tag_extra = 'readonly';
    $this->mm->tag_extra = 'readonly';
    $this->yy->tag_extra = 'readonly';
  }

  function html ()
  {
    $ret = myform_date::html();
    $ret .= '<button class='.$this->buttonClass.' onclick="var r=show_dialog(\'calendar\', \'Select date\',142,208);if(r){this.form.'.$this->dd->key.'.value=r[\'dd\'];this.form.'.$this->mm->key.'.value=r[\'mm\'];this.form.'.$this->yy->key.'.value=r[\'yy\'];}this.blur();"></button>';
    return $ret;
  }
}
//-------------------------------------------------------------------
class myform_file_select extends myform_textbox
{
  var $dialog;
  var $txtName;
  var $btn1Class = 'spBtn1';
  var $btn2Class = 'spBtn2';
  var $dialogTitle;
  var $jsBefore = '';
  var $jsAfter = '';
  var $onClear = '';

  function myform_file_select ($form_name, $name, $dialog, $classTxt = '', $btn1_class = '', $btn2_class = '')
  {
    $this->myform_textbox ($form_name, $name, $classTxt, 0, 128);

    $this->dialog = $dialog;
    if (strlen ($btn1_class)) $this->btn1Class = $btn1_class;
    if (strlen ($btn2_class)) $this->btn2Class = $btn2_class;
    $this->dialogTitle = '';
  }

  function html ()
  {
    $this->tag_extra = trim ($this->tag_extra.' readonly onclick="'.$this->key.'_btn1.click();" style="cursor:hand;"');
    $this->value = $this->value;

    $btn =
    '&nbsp;<button id="'.$this->key.'_btn1" class="'.$this->btn1Class.'" onclick="'.$this->jsBefore.'var r=dialog_show(\''.$this->dialog.'\',\''.$this->dialogTitle.'\');if(r){this.form.'.$this->key.'.value=r[\'path\'];}this.blur();'.$this->jsAfter.'"></button>'.
    '&nbsp;<button class="'.$this->btn2Class.'" onclick="this.form.'.$this->key.'.value=\'\';'.$this->onClear.'this.blur();"></button>';

    $txt =
    '<table cellspacing=0 cellpadding=0 border=0 width=100%>'.
      '<tr>'.
        '<td width=100%>'.myform_textbox::html().'</td>'.
        '<td>'.$btn.'</td>'.
      '</tr>'.
    '</table>';

    return $txt;
  }
}
//-------------------------------------------------------------------
class myform_editable_list extends myform_control
{
  var $dialog;
  var $dialogTitle;
  var $dialogLabel;
  var $dialogModule;
  var $dialogType = 0;
  var $dialogKey = 'id';
  var $dialogValue = 'name';
  var $arr;
  var $allowEdit = true;
  var $fullWidth = true;
  var $moveButtons = true;
  var $useKeys = true;
  var $separatorCode = 0;

  function myform_editable_list ($form_name, $name, $className = '')
  {
    $this->myform_control ($form_name, $name);
    $this->dialog = 'name';
    $this->class = $className;
    $this->arr = array ();
    $this->dialogLabel = '';
    $this->dialogTitle = '';
    $this->dialogModule = '';
    $this->separatorCode = ord (',');
  }

  function html ()
  {
    if (strlen ($this->value) && !sizeof ($this->arr)) $this->arr = $this->decode ($this->value);

    $opt = '';
    if (is_array ($this->arr))
    {
      foreach ($this->arr as $k => $v) $opt .= '<option value="'.$k.'">'.$v.'</option>';
    }

    $add_extra = 'style="width:100%;height:90px"';
    if ($this->allowEdit) $add = ' ondblclick="myforms_editable_list_edit(this)"'; else $add = '';
    $extra = $this->generate_extra ();
    if (!strlen ($extra)) $extra = $add_extra;
    $class = '';
    if (strlen ($this->class)) $class = ' class="'.$this->class.'"';
    if ($this->fullWidth) $ww = ' width="100%"'; else $ww = '';

    $buttons =
    '<button class="spBtnPlus" style="margin-bottom:2px" title="Add" onclick="myforms_editable_list_add(this.form.'.$this->key.');this.blur();"></button><br>'.
    '<button class="spBtnMinus" style="margin-bottom:2px" title="Delete" onclick="myforms_editable_list_delete(this.form.'.$this->key.');this.blur();"></button>';

    if ($this->moveButtons)
    {
      $buttons .=
      '<br>'.
      '<button class="spBtnUp" style="margin-bottom:2px" title="Up" onclick="myforms_editable_list_move(this.form.'.$this->key.',-1);this.blur();"></button><br>'.
      '<button class="spBtnDown" title="Down" onclick="myforms_editable_list_move(this.form.'.$this->key.',1);this.blur();"></button>';
    }

    return
    '<table cellspacing="0" cellpadding="0" border="0"'.$ww.'>'.
      '<tr valign="top">'.
        '<td'.$ww.'><select name="'.$this->key.'"'.$class.' size="7" '.$extra.$add.' dialog="'.$this->dialog.'" dialogTitle="'.$this->dialogTitle.'" dialogLabel="'.$this->dialogLabel.'" dialogModule="'.$this->dialogModule.'" dialogType="'.$this->dialogType.'" dialogKey="'.$this->dialogKey.'" dialogValue="'.$this->dialogValue.'">'.$opt.'</select></td>'.
        '<td style="padding:5 0 0 5" valign="top">'.$buttons.'</td>'.
    '</table>'.
    '<input type="hidden" name="'.$this->key.'_data" value="'.htmlspecialchars ($this->encode($this->arr, true)).'">';
  }

  function load_data ($r)
  {
    if (is_array ($r))
    {
      $this->arr = $r;
      $this->value = $this->encode ($r);
    }
  }

  function get_data ()
  {
    return $this->decode ($this->value);
  }

  function encode ($r, $force = false)
  {
    $s = '';
    if (!is_array ($r) || !sizeof ($r)) return $s;
    if ($this->useKeys || $force)
    {
      foreach ($r as $k => $v) $s .= '<#'.$k.'#><#'.$v.'#>';
    }
    else
    {
      $s = join (chr($this->separatorCode), $r);
    }
    return $s;
  }

  function decode ($s, $force = false)
  {
    $r = array ();
    $s = trim ($s);
    if (!strlen ($s)) return $r;
    if ($this->useKeys || $force)
    {
      preg_match_all ('/<#(.*)#>/isU', $s, $t);
      $cnt = sizeof ($t[1]);

      $n = 0;
      for ($i=0; $i<$cnt; $i+=2)
      {
        if ($t[1][$i] == 0)
        {
          $t[1][$i] = 'n'.$n;
          $n ++;
        }
        $r[$t[1][$i]] = $t[1][$i + 1];
      }
    }
    else
    {
      $r = explode (chr ($this->separatorCode), $s);
    }
    return $r;
  }

  function process ()
  {
    if (isset ($_POST[$this->key.'_data']))
    {
      $val = $_POST[$this->key.'_data'];
      $val = stripslashes ($val);
      $val = trim ($val);

      $r = $this->decode ($val, true);
      $this->load_data ($r);
    }
  }
}
//-------------------------------------------------------------------
class myform_list_selector extends myform_control
{
  var $obj1;
  var $obj2;
  var $data;
  var $showUpDownButtons = true;
  var $width = 400;

  function myform_list_selector ($form_name, $name, $class = '')
  {
    $this->myform_control ($form_name, $name);
    $this->class = $class;

    $this->obj1 = new myform_combobox ($form_name, $name.'_1', false, false);
    $this->obj2 = new myform_combobox ($form_name, $name.'_2', false, false);

    $this->obj1->tag_extra = 'size="10" MULTIPLE style="height:150px" ondblclick="document.getElementById(\''.$this->name.'_btn1\').click();"';
    $this->obj2->tag_extra = 'size="10" MULTIPLE style="height:150px" ondblclick="document.getElementById(\''.$this->name.'_btn2\').click();"';

    $this->data = array ();
  }

  function html ()
  {
    $this->obj1->class = $this->class;
    $this->obj2->class = $this->class;

    $val = array ();
    if (strlen ($this->value)) $val = explode (',', $this->value);

    $r1 = $r2 = array ();
    $value = '';
    foreach ($this->data as $k => $v)
    {
      if (!in_array ($k, $val)) $r1[$k] = $v; else $r2[$k] = $v;
    }

    $cnt = sizeof ($val);
    $tmp = array ();
    for ($i=0; $i<$cnt; $i++)
    {
      if (isset ($r2[$val[$i]])) $tmp[$val[$i]] = $r2[$val[$i]];
      $value .= $val[$i].',';
    }
    $r2 = $tmp;
    unset ($tmp);
    if (strlen ($value)) $value = substr ($value, 0, -1);


    $this->obj1->loadArray ($r1);
    $this->obj2->loadArray ($r2);

    $s =
    '<table cellspacing="0" cellpadding="0" border="0" width="'.$this->width.'">'.
      '<tr>'.
        '<td width="50%">'.$this->obj1->html().'</td>'.
        '<td style="padding:0 3"><input id="'.$this->name.'_btn1" type="button" class="spBtnRight" onclick="myforms_list_selector_move_right(this.form.'.$this->name.'_1,this.form.'.$this->name.'_2);this.blur();"><br><input id="'.$this->name.'_btn2" type="button" class="spBtnLeft" onclick="myforms_list_selector_move_left(this.form.'.$this->name.'_1,this.form.'.$this->name.'_2);this.blur();" style="margin-top:5px"></td>'.
        '<td width="50%">'.$this->obj2->html().'</td>';
     if ($this->showUpDownButtons)
     {
        $s .= '<td style="padding:0 3"><button class="spBtnUp" onclick="myforms_list_selector_move_up(this.form.'.$this->name.'_2);this.blur();"></button><br><button class="spBtnDown" onclick="myforms_list_selector_move_down(this.form.'.$this->name.'_2);this.blur();" style="margin-top:5px"></button></td>';
     }
    $s .=
      '</tr>'.
    '</table><input type="hidden" name="'.$this->name.'" value="'.$value.'">';

    return $s;
  }

  function loadArray ($r)
  {
    if (!is_array ($r)) return false;
    $this->data = $r;
  }
}
//-------------------------------------------------------------------
class myform_file_jpeg extends myform_file
{
  var $maxSize = 30000;
  var $maxWidth = 300;
  var $maxHeight = 300;
  var $errors;
  var $imgData;
  var $allowed;
  var $extensions;

  function myform_file_jpeg ($form_name, $name, $path = './', $filename = '', $class = '', $size = 0)
  {
    if (!strlen ($filename)) $filename = time().'.jpg';
    $this->myform_file ($form_name, $name, $path, $filename, $class, $size);
    $this->allowed = array (2);
    $this->extensions = array (1 => 'gif', 2 => 'jpg', 3 => 'png');
  }

  function process ()
  {
    if (isset($_FILES[$this->key]))
    {
      $path = $_FILES[$this->key]['tmp_name'];
      if (is_file ($path))
      {
        $fsize = filesize ($path);
        $tmp = getImageSize ($path);
        $this->imgData = $tmp;

        if (in_array ($tmp[2], $this->allowed) && $tmp[0]<=$this->maxWidth && $tmp[1]<=$this->maxHeight && $fsize <= $this->maxSize)
        {
          $a = pathinfo($this->filename);
          $this->filename = preg_replace ('/\.'.$a['extension'].'$/isU', '.'.$this->extensions[$tmp[2]], $this->filename);
          myform_file::process ();
          chmod ($this->path.$this->value, 0666);
        }
        else
        {
          $this->errors = array ();
          if (!in_array ($tmp[2], $this->allowed)) $this->errors[] = 'type';
          if ($tmp[0] > $this->maxWidth) $this->errors[] = 'width';
          if ($tmp[1] > $this->maxHeight) $this->errors[] = 'height';
          if ($fsize > $this->maxSize) $this->errors[] = 'size';

          # genereating error for the form class
          $this->preg_check = '/^$/isU';
          $this->value = 'error';
        }
      }
    }
  }
}
//-------------------------------------------------------------------
class myform_url_select extends myform_textbox
{
  var $dialog = 'links';
  var $dialogTitle = '';
  var $btn1Class = 'spBtn1';
  var $btn2Class = 'spBtn2';
  var $objName;
  var $objTarget;

  function myform_url_select ($form_name, $name, $className = '')
  {
    $this->myform_textbox ($form_name, $name, $className, false, 512);
  }

  function html ()
  {
    $this->tag_extra = trim ('onblur="myforms_url_select_add_http(false);" onpaste="myforms_url_select_add_http(true);" '.$this->tag_extra);

    $add_js = '';
    if (strlen ($this->objName)) $add_js .= 'if(r && r[\'name\'] && this.form.'.$this->objName.'.value.length==0) {this.form.'.$this->objName.'.value=r[\'name\'];}';
    if (strlen ($this->objTarget)) $add_js .= 'if(r && r[\'url\'] && r[\'page\']) {this.form.'.$this->objTarget.'.value=r[\'page\'];}';

    $isTarget = 'NaN';
    if (strlen ($this->objTarget)) $isTarget = 'this.form.'.$this->objTarget;

    $btn =
    '&nbsp;<button class="'.$this->btn1Class.'" onclick="myforms_url_select_set_cookies(this.form.'.$this->key.','.$isTarget.');var r=dialog_show(\''.$this->dialog.'\',\''.$this->dialogTitle.'\');if(r && r[\'id\']){this.form.'.$this->key.'.value=\'{\'+r[\'id\']+\'}\';} else if(r && r[\'protocol\'] && r[\'url\']) {this.form.'.$this->key.'.value=r[\'protocol\']+r[\'url\'];}'.$add_js.'this.blur();"></button>'.
    '&nbsp;<button class="'.$this->btn2Class.'" onclick="this.form.'.$this->key.'.value=\'\';this.blur();"></button>';

    $txt =
    '<table cellspacing=0 cellpadding=0 border=0 width=100%>'.
      '<tr>'.
        '<td width=100%>'.myform_textbox::html().'</td>'.
        '<td>'.$btn.'</td>'.
      '</tr>'.
    '</table>';

    return $txt;
  }
}
//-------------------------------------------------------------------
?>