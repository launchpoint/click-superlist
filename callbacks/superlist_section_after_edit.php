<?

$s = SuperlistSection::find_by_id($params['SuperlistSection']['oid']);

$fields = SuperlistSectionField::find_all( array(
  'conditions'=>array('superlist_section_id in (select id from superlist_sections where superlist_id = ?)', $s->superlist_id),
  'order'=>'weight',
));

$new_object = SuperlistSectionField::new_model_instance(array(
  'attributes'=>array(
    'superlist_section_id'=>$s->id,
  ),
));

$meta = array(
  'klass'=>'SuperlistSectionField',
  'objects'=>$fields,
  'new_object'=>$new_object,
  'list'=>array('name', 'query_name'),
  'sections'=>array(
    'basic'=>array(
      'name'=>array('readonly'=>true),
      'query_id',
    )
  ),
  'controls'=>array('add'=>false, 'delete'=>false),
);

superlist($meta);
