<?

function superlist_section_get_calced_fields($s)
{
  $fields = array();
  foreach($s->superlist_section_fields( array('order'=>'weight')) as $f)
  {
    $fields[] = (object)array('id'=>$f->name, 'name'=>$f->name);
  }
  return $fields;
}

function superlist_section_get_available_calced_fields($s)
{
  global $model_settings;
  $columns = array();
  foreach($model_settings[$s->superlist->class]['type'] as $column_name=>$type_info)
  {
    if($column_name == 'id') continue;
    $columns[] = (object)array('id'=>$column_name, 'name'=>$column_name);
  }
  return $columns;
}

function superlist_section_update_calced_fields($s, $ids)
{
  query("delete from superlist_section_fields where superlist_section_id = ?", $s->id);
  $i=1;
  foreach($ids as $id)
  {
    SuperlistSectionField::create( array(
      'attributes'=>array(
        'superlist_section_id'=>$s->id,
        'name'=>$id,
        'weight'=>$i++,
      ),
    ));
  }
}

function superlist_section_get_class__d($s)
{
  return $s->superlist->class;
}