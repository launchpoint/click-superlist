<?

function superlist_section_field_get_type_info__d($s)
{
  return eval("return {$s->class}::\$attribute_types['{$s->name}'];");
}

function superlist_section_field_get_class__d($s)
{
  return $s->superlist_section->class;
}

function superlist_section_field_get_available_queries__d($s)
{
  return StoredQuery::find_all( array(
    'order'=>'name'
  ));
}

function superlist_section_field_get_query_name__d($s)
{
  if(!$s->query) return '-';
  return $s->query->name;
}