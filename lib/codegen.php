<?

function codegen_superlist($obj, $collection_name, $meta)
{
  if(!isset($meta['klass']))
  {
    $hm = eval("return {$obj->klass}::\$has_many;");
    if(!isset($hm[$collection_name]))
    { 
      click_error("$collection_name is not a 1:n association of {$obj->klass}. You mean to use an 1:n here.");
    }
    
    $info = eval("return {$obj->klass}::\$has_many[\$collection_name];");
    $tn = $info[0];
    $meta['klass'] = classify(singularize($tn));
  }
  $meta['objects'] = $obj->$collection_name;
  $meta['container_name'] = $obj->name;

  $meta = superlist_build_meta($meta);
  
  $meta['new_object']->$info[1] = $obj->id;
  
  if(isset($model_info['type']['weight'])) $obj->sort($collection_name, 'weight');
  
  superlist($meta);
}
