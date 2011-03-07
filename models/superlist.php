<?

function superlist_get_calced_params($s)
{
  $params = array();
  
  foreach($s->superlist_activerecord_params as $p)
  {
    $params[$p->name] = eval("return ".trim($p->value).";");
  }
  
  return $params;
}

function superlist_get_calced_list_columns($s)
{
  $cols = array();
  foreach($s->superlist_list_columns( array('order'=>'weight')) as $c)
  {
    $cols[] = $c->name;
  }
  return $cols;
}

function superlist_get_calced_sections($s)
{
  $sections = array();
  foreach($s->superlist_sections as $sec)
  {
    $sections[$sec->name] = array();
    $fields = $sec->superlist_section_fields( array('order'=>'weight'));
    foreach($fields as $f)
    {
      $type_info = $f->type_info;
      if($f->query)
      {
        $type_info['type'] = 'select';
        $type_info['item_array'] = $f->query->function_name;
      }
      $sections[$sec->name][$f->name] = $type_info;
    }
  }
  return $sections;
}

function superlist_get_name__d($s)
{
  return $s->key;
}

function superlist_get_objects__d($s)
{
  return call_user_func( array($s->class, 'find_all'), $s->calced_params);  
}