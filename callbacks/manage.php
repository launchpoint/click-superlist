<?

$s = Superlist::find_or_new_by_key($params['key'], array(
  'attributes'=>array(
    'key'
  )
));

global $models;
$s->superform_sections = array(
  'basic'=>array(
    'key',
    'class'=>array('type'=>'select', 'item_array'=>$models, 'autopostback'=>true),
  )
);


if(is_postback('superlist'))
{
  switch(p('superform_cmd'))
  {
    case 'refresh':
      $s->update_attributes($params['superlist'],false);
      break;
    case 'commit':
      $s->update_attributes($params['superlist']);
      if($s->is_valid)
      {
        flash('Superlist saved.');
      }
      break;
  }
}

if($s->class)
{
  $o = eval("return {$s->class}::new_model_instance();");
  if(!$o->responds_to('name'))
  {
    $s->superform_sections['basic']['name_function_body'] = array(
      'help'=>array(
        'title'=>'"Name" Function Body',
        'body'=>'The PHP function body used to evaluate $obj->name, if $obj->name does not exist already.',
      ),
    );
  }
}