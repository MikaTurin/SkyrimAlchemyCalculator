<?php
define ('MYFORM_AND', 1);
define ('MYFORM_OR', 0);

class myform
{
    var $name;    // name of the table
    var $action;  // url to the submit page
    var $method = 'post';
    var $fields;  // array of controls objects
    var $show_values = false;
    var $enctype = '';
    var $errors = array();
    var $rules = array();
    var $tag_extra = '';
    var $cellspacing = 0;
    var $classname = '';
    var $tableWidth = '';
    var $wasProcess = false;

    function myform($name = 'frm', $action = "")
    {
        $this->fields = array();
        $this->name = $name;
        if (!strlen($action)) {
            $action = basename($_SERVER['PHP_SELF']);
        }
        $this->action = $action;
    }

    function destroy()
    {
        foreach ($this->fields as $field) {
            $this->fields[$field->name]->destroy();
        }
    }

    /**
     * @param $control
     * @param $name
     * @param null $param1
     * @param null $param2
     * @param null $param3
     * @param null $param4
     * @param null $param5
     * @param null $param6
     * @return myform_control
     */
    function add_control(
        $control,
        $name,
        $param1 = null,
        $param2 = null,
        $param3 = null,
        $param4 = null,
        $param5 = null,
        $param6 = null
    ) {
        $str = '';
        for ($i = 1; $i <= 6; $i++) {
            $var = 'param' . $i;
            if (!($$var === null)) {
                $str .= ", \$$var";
            } else {
                break;
            }
        }
        $str = "\$this->fields['$name'] = new myform_$control (\$this->name, '$name' $str);";
        eval ($str);
        if ($control == 'xbeditor') {
            $this->tag_extra = ' onsubmit="return xbUpdateField(this.' . $name . ');"';
        }

        return $this->fields[$name];
    }

    function set_label($key, $label)
    {
        if (isset ($this->fields[$key])) {
            $this->fields[$key]->label = $label;
        }
    }

    function setLabel($key, $label)
    {
        $this->set_label($key, $label);
    }

    function set_preg($key, $preg)
    {
        $this->setPreg($key, $preg);
    }

    function setPreg($key, $preg)
    {
        if (isset ($this->fields[$key])) {
            $this->fields[$key]->preg_check = $preg;
        }
    }

    function set_rule($key, $arr, $condition = MYFORM_AND, $inverse = 0)
    {
        $this->rules[$key] = array('rule' => $arr, 'condition' => $condition, 'inverse' => $inverse);
    }

    function html_submit($button_text, $class = '', $extra = '', $type = 1)
    {
        if (strlen($class)) {
            $class = ' class="' . $class . '"';
        }
        if (strlen($extra)) {
            $extra = ' ' . $extra;
        }

        $ret = '';

        if ($type == 3) {
            $ret .=
                '<input type="image" border="0" alt="' . $button_text . '"' . $extra . '>';
        } elseif ($type == 2) {
            $ret .=
                '<button' . $class . $extra . '>' . $button_text . '</button>';
        } else {
            $ret .=
                '<input type="submit" value="' . $button_text . '"' . $class . $extra . '>';
        }


        return $ret;
    }

    function draw_submit($button_text, $class = '', $extra = '', $type = 1)
    {
        echo $this->html_submit($button_text, $class, $extra, $type);
    }

    function form_begin()
    {
        $enctype = $add = '';
        if (strlen($this->enctype)) {
            $enctype = ' enctype="' . $this->enctype . '"';
        }
        if (strlen($this->tag_extra)) {
            $add = ' ' . $this->tag_extra;
        }
        return '<form name="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '"' . $enctype . $add . '>';
    }

    function begin()
    {
        return $this->form_begin();
    }

    function end()
    {
        return
            '<input type="hidden" name="' . $this->name . '_myfrm_sbm" value="1">' .
            '</form>';
    }

    function html($border = 0, $hide_submit = false, $hide_labels = false)
    {
        if (strlen($this->tableWidth)) {
            $ww = ' width="' . $this->tableWidth . '"';
        } else {
            $ww = '';
        }

        $ret = $this->form_begin() .
            '<table cellspacing="' . $this->cellspacing . '" cellpadding="0" border="' . $border . '"' . $ww . '>';

        foreach ($this->fields as $field) {
            $label = '';
            $name = $field->name;
            if (strlen($field->label)) {
                $name = $field->label;
            }
            if (!$hide_labels) {
                $label = '<td valign="top" class="' . $this->classname . '">' . $name . '</td>';
            }

            $ret .=
                '<tr>' . $label .
                '<td' . $ww . '>' . $field->html() . '</td>' .
                '</tr>';
        }

        if (!$this->show_values && !$hide_submit) {
            $ret .= '<tr><td colspan="2" align="right"><br>';
            $ret .= $this->html_submit('Submit');
            $ret .= '</td></tr>' . "\n";
        }

        $ret .=
            '</table>' . "\n" . $this->end() . "\n";

        return $ret;
    }

    function draw($border = 0)
    {
        echo $this->html($border);
    }

    function draw_value()
    {
        $this->show_values = true;
        foreach ($this->fields as $field) {
            $this->fields[$field->name]->draw_value = true;
        }
    }

    function draw_html()
    {
        $this->show_values = false;
        foreach ($this->fields as $field) {
            $this->fields[$field->name]->draw_value = false;
        }
    }

    function is_submited()
    {
        return $this->isSubmited();
    }

    function isSubmited()
    {
        return isset($_POST[$this->name . '_myfrm_sbm']);
    }

    function process($force = false)
    {
        if ($this->wasProcess && $force == false) {
            return true;
        }

        if (!isset($_POST[$this->name . '_myfrm_sbm'])) {
            return false;
        }

        $this->errors = array();
        foreach ($this->fields as $key => $field) {
            $this->fields[$key]->process();
            if ($this->fields[$key]->preg_check) {
                if (!preg_match($this->fields[$key]->preg_check, $this->fields[$key]->value)) {
                    $this->errors[] = $field->name;
                }
            }
        }

        $this->check_rules();
        if (sizeof($this->errors)) {
            return false;
        }
        $this->wasProcess = true;
        return true;
    }

    function check_rules()
    {
        foreach ($this->rules as $key => $arr) {
            if (!is_array($arr['rule'])) {
                $arr['rule'] = array();
            }
            $arr['results'] = array();

            $cond = '||';
            if ($arr['condition']) {
                $cond = '&&';
            }

            $str = '';

            foreach ($arr['rule'] as $field => $val) {
                $str .= preg_match($val, $this->fields[$field]->value) . ' ' . $cond . ' ';
            }
            $str = substr($str, 0, -4);
            eval ('$res = (' . $str . ');');
            if ($arr['inverse']) {
                $res = !$res;
            }
            if ($res) {
                $this->errors[] = $key;
            }
        }
    }

    function clear()
    {
        foreach ($this->fields as $field) {
            $this->fields[$field->name]->value = null;
        }
    }

    public function getValue($name)
    {
        return $this->fields[$name]->value;
    }

    public function getValues()
    {
        $r = array();
        foreach ($this->fields as $field) {
            $r[$field->name] = $field->value;
        }
        return $r;
    }
}
//-------------------------------------------------------------------
class mysourceform extends myform
{
  var $mode;           // mode of the form, 0=insert, 1=update, 2=delete
  var $value;          // identifier value

  // Events fired just before executing the specified action
  // it must return true, so the process may continue
  // if it returns false, no action is taken
  var $before_insert;    // mode is sended as first paramet, fields & values are sended in array as second parametr
  var $before_update;    // mode is sended as first paramet, fields & values are sended in array as second parametr
  var $before_delete;    // record id is sended as first parametr

  // Events fired just after record have been added or updated
  var $after_insert; // mode is sended as first paramet, id is sended as second parametr, fields & values are sended in array as third parametr
  var $after_update; // mode is sended as first paramet, id is sended as second parametr, fields & values are sended in array as third parametr
  var $after_delete;

  function mysourceform ($name)
  {
    $this->name = $name;
    $this->myform ($name); // call parents constructor
  }

  function html_submit ($button_text, $class = '', $extra = '', $type = 1)
  {
    return
    '<input type="hidden" name="'.$this->name.'_myfrm_mode" value="'.$this->mode.'">'.
    '<input type="hidden" name="'.$this->name.'_myfrm_key" value="'.$this->value.'">'.
    myform::html_submit ($button_text, $class, $extra, $type);
  }

  function select_data()
  {
    return false;
  }

  function insert_data ($update = false)
  {
    return 0;
  }

  function process (&$error_code)
  {
    $this->mode = 0;

    if (!myform::process ())
    {
      // if this form didn`t processed try to see if the user has set value
      // how user can set value? using $form->value = "xxx"
      if (strlen ($this->value) > 0)
      {
        //Something filled in value, try loading the values into mysourceform
        if ($this->select_data())
        {
          //found data!
          $this->mode = 1;
        }
        else
        {
          // some error occurred, clear mysourceform
          $this->clear ();
        }
      }
    }
    else
    {
      // the form processed anything, lets work
      // first get key and value
      if (isset($_POST[$this->name."_myfrm_mode"]) && isset($_POST[$this->name."_myfrm_key"]))
      {
        $this->mode = $_POST[$this->name."_myfrm_mode"];
        $this->value = $_POST[$this->name."_myfrm_key"];
      }

      // if delete button was pressed, goto deletemode (not supported in this version)
      //if (isset ($_POST["del_sbm"])) {$this->mode = 2;}

      if ($this->mode == 0)
      {
        // inserting data
        $values = $this->get_fields_value_arr ();
        if (isset ($this->before_insert))
        {
          $func = $this->before_insert;
          if (!$func($this->mode, $values, $error_code)) {return false;}
        }

        $this->value = $this->insert_data (false);

        if (isset ($this->after_insert))
        {
          $func = $this->after_insert;
          $func($this->mode, $this->value, $r);
        }
        $this->mode = 1;
        return true;
      }
      elseif ($this->mode == 1)
      {
        // updating data
        $values = $this->get_fields_value_arr ();

        if (isset ($this->before_update))
        {
          $func = $this->before_update;
          if (!$func($this->mode, $values, $error_code)) {return false;}
        }

        $this->value = $this->insert_data (true);

        if (isset ($this->after_update))
        {
          $func = $this->after_update;
          $func($this->mode, $this->value, $values);
        }

        return true;
      }
      elseif ($this->mode == 2)
      {
        // deleting data

      }
    }
  }
}
//-------------------------------------------------------------------
class mydbform extends mysourceform
{
  var $handle;         // handle to database connection
  var $table;          // table where information is stored
  var $key;            // primary field of the table (if more then one separeted by "|")
  var $keys_arr;       // array with the names and values of the primary key (if fields is more than one)
  var $keys_cnt;       // number of primary key fields
  var $dbfields;       // informatation about fields in the table
  var $sfield;         // information is saved as array in this field

  function mydbform ($handle, $table, $primary_key, $name = '', $sfield = '')
  {
    $this->handle = $handle;
    $this->table = $table;
    $this->key = $primary_key;
    $this->keys_arr = array ();
    $this->value = '';
    $this->sfield = $sfield;
    $this->name = $name;
    if (!strlen ($name)) {$this->name = $this->table;} //if empty name use tablename as formname

    $this->myform ($name); // call parents constructor
    $this->fill_dbfields (); //getting fields descriptions if there is no serialize

    // checking the number of the primary key fieldss
    $tmp = explode ('|', $this->key);
    $this->keys_cnt = sizeof ($tmp);
    for ($i=0; $i<$this->keys_cnt; $i++) {$this->keys_arr[$i] = array ('key' => $tmp[$i]);}
  }

  function add_control ($control, $name, $param1 = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL, $param5 = NULL, $param6 = NULL)
  {
          $isdb = isset ($this->dbfields[$name]);

    if ($isdb && $control == 'textbox')
    {
            if (!intval ($param3)) {$param3 = $this->dbfields[$name]['Length'];}
    }

    myform::add_control ($control, $name, $param1, $param2, $param3, $param4, $param5, $param6);

    $default = array ('textbox', 'checkbox', 'checkbox_list', 'combobox', 'date', 'combo_date');

    if ($isdb && $control == 'radiobox')
    {
            if (!$param3) {$this->fields[$name]->value = $this->dbfields [$name]['Default'];}
    }
    elseif ($isdb && in_array ($control, $default))
    {
            $this->fields[$name]->value = $this->dbfields [$name]['Default'];
    }
  }

  function perror ($string, $txt = '')
  {
    echo '<font color="red"><b>SQL error: </b></font>'.$string.'<br>';
    if (strlen ($txt)) {echo '<font color="red"><b>SQL: </b></font>'.$txt.'<br>';}
    echo '<br>';
    return 0;
  }

  function slash ($text)
  {
    $text = addslashes ($text);
    return $text;
  }

  function uquery ($query)
  {
    if (!$cursor = mysql_query ($query, $this->handle)) {return $this->perror ('query', $query);}
    return true;
  }

  function get_one_arr ($query)
  {
          //echo $query.'<br>';

    // function return only first record from the dataset
    if(!$cursor = mysql_query($query, $this->handle)) {return $this->perror ('get_one_arr', $query);}

    $row = array ();
    $sum = mysql_num_rows ($cursor);

    if ($sum)
    {
      $row1 = mysql_fetch_array ($cursor, MYSQL_ASSOC);
      foreach ($row1 as $key => $val) {$row[$key] = $val;}
    }
    mysql_free_result ($cursor);
    return $row;
  }

  function get_arr ($query)
  {
    if(!$cursor = mysql_query($query, $this->handle)) {return $this->perror ('get_arr', $query);}

    $row = array ();
    $sum = mysql_num_rows ($cursor);

    if ($sum)
    {
      $i = 0;
      $row1 = mysql_fetch_array ($cursor, MYSQL_ASSOC);
      while ($row1)
      {
        foreach ($row1 as $key => $val) {$row[$i][$key] = $val;}
        $i++;
        $row1 = mysql_fetch_array ($cursor, MYSQL_ASSOC);
      }
    }
    mysql_free_result ($cursor);
    return $row;
  }

  function fill_dbfields ()
  {
    $r = $this->get_arr ('SHOW COLUMNS FROM '.$this->table);
    $cnt = sizeof ($r);
    $this->dbfields = array ();

    for ($i=0; $i<$cnt; $i++)
    {
      $r[$i]['Length'] = intval (preg_replace ('/^.*\(([0-9]+)\).*$/isU', "\\1", $r[$i]['Type']));
      $this->dbfields[$r[$i]['Field']] = $r[$i];
    }
  }

  function explode_value ()
  {
    $tmp = explode ('|', $this->value);
    $cnt = sizeof ($tmp);

    for ($i=0; $i<$cnt; $i++)
    {
      $this->keys_arr[$i]['value'] = $tmp[$i];
    }
  }

  function get_where_condition ()
  {
          if ($this->keys_cnt == 1)
          {
      return ' WHERE '.$this->key.'="'.$this->value.'"';
    }
    else
    {
      $this->explode_value ();
      $where = '';
      for ($i=0; $i<$this->keys_cnt; $i++)
      {
        $where .= $this->keys_arr[$i]['key'].'="'.$this->keys_arr[$i]['value'].'"';
        if ($i + 1 < $this->keys_cnt) {$where .= ' AND ';}
      }
      return ' WHERE '.$where;
    }
  }

  function select_data ()
  {
    if (strlen ($this->sfield)) {return $this->select_serialized_data ();}

    $q = "SELECT ";
    $cnt = sizeof ($this->fields);
    if (!$cnt) {return false;}

    // adding fields after SELECT statment
    foreach ($this->fields as $field)
    {
      $q .= $field->name.',';
    }
    $q = substr ($q, 0, -1);

    $q .= ' FROM '.$this->table;

    // WHERE conditions
    $q .= $this->get_where_condition ();

    // adding query result to the field objects
    if ($r = $this->get_one_arr ($q))
    {
      foreach ($this->fields as $field)
      {
        $this->fields[$field->name]->value = $r[$field->name];
      }
    }

    return true;
  }

  function select_serialized_data ()
  {
    if (!strlen ($this->sfield)) {return false;}

    $q = 'SELECT '.$this->sfield.' FROM '.$this->table.$this->get_where_condition ();
    if ($r = $this->get_one_arr ($q))
    {
            $tmp = unserialize ($r[$this->sfield]);
      foreach ($this->fields as $field)
      {
        $this->fields[$field->name]->value = $tmp[$field->name];
      }
      return true;
    }
    return false;
  }

  function insert_data ($update = false)
  {
          //checking existence of the row with key=value
          $r = $this->get_one_arr ('SELECT COUNT(*) AS cnt FROM '.$this->table.$this->get_where_condition ());
          if (!$r['cnt'])
          {
                  $this->mode = 0;
                  $this->value = '';
                  $update = false;
          }

    if (strlen ($this->sfield)) {return $this->insert_serialized_data ($update);}

    $q = '';
    $cnt = sizeof ($this->fields);
    if (!$cnt) {return false;}

    foreach ($this->fields as $field)
    {
            if (strlen ($field->value) || $field->save_empty)
            {
        $q .= $field->name.'="'.$this->slash ($field->value).'",';
      }
    }

    $q = substr ($q, 0, -1);

    if (!$update)
    {
      $q = 'INSERT INTO '.$this->table.' SET '.$q;
      if (!$this->uquery ($q)) {return false;}
      if ($this->keys_cnt == 1) {return mysql_insert_id ($this->handle);}
    }
    else
    {
      $q = 'UPDATE '.$this->table.' SET '.$q.$this->get_where_condition ();
      if (!$this->uquery ($q)) {return false;}
      return $this->value;
    }
  }

  function insert_serialized_data ($update = false)
  {
    $keys = '';

    for ($i=0; $i<$this->keys_cnt; $i++)
    {
      $key = $this->keys_arr[$i]['key'];
      $value = $this->keys_arr[$i]['value'];

      if (!isset ($r[$key]))
      {
        $r[$key] = $value;
      }
      if (strlen ($value) && !$update)
      {
        $keys .= $key.'="'.$this->slash ($value).'",';
      }
    }

    $r = array ();
    foreach ($this->fields as $field)
    {
      $r[$field->name] = $field->value;
      if (isset ($this->dbfields[$field->name]))
      {
               $keys .= $field->name.'="'.$this->slash ($field->value).'",';
      }
    }

    $ser = serialize ($r);

    if (!$update)
    {
      $q = 'INSERT INTO '.$this->table.' SET '.$keys.$this->sfield.'="'.$this->slash ($ser).'"';
      if (!$this->uquery ($q)) {return false;}
      if ($ret = mysql_insert_id ($this->handle))
      {
              $this->value = $ret;
              return $ret;
      }
      return $this->value;
    }
    else
    {
      $q = 'UPDATE '.$this->table.' SET '.$keys.$this->sfield.'="'.$this->slash ($ser).'"'.$this->get_where_condition ();
      if (!$this->uquery ($q)) {return false;}
      return $this->value;
    }
  }
}
?>