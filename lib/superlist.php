<?



function superlist($meta, $objects=null)
{
  if(is_array($meta))
  {
    $meta = superlist_build_meta($meta);
    
    $name = isset($meta['container_name']) ? $meta['container_name'] : 'Parent'; 
    event('superlist', array('meta'=>$meta));
  } else {
    superlist_dynamic($meta);
  }
}

function superlist_dynamic($key)
{
  $s = Superlist::find_by_key($key);
  
  if(!$s)
  {
    redirect_to(manage_superlist_url($key));
  }
  
  $objs = $s->objects;
  $meta = array(
    'builder'=>$s,
    'klass'=>$s->class,
    'objects'=>$objs,
    'list'=>$s->calced_list_columns,
    'sections'=>$s->calced_sections,
  );
  
  superlist($meta);  
}

function superlist_format($value, $field_info)
{
  if(!isset($field_info['type'])) dprint($field_info);
  switch($field_info['type'])
  {
    case 'date':
      return click_date_format($value);
    case 'check':
      return $value ? 'Yes' : 'No';
    case 'title':
      return humanize($value);
    case 'currency':
      return currency_format($value);
    case 'float':
      return round($value,2);
    case 'tinyint':
      return $value ? 'Yes' : 'No';
    case 'image':
      if(!$value) return '';
      return image_tag(magick_img_url('icon', $value->vpath, array('zoomcrop'=>true)));
    case 'mutex':
      return join(', ',collect($value, $field_info['display_field']));
    case 'textarea':
    case 'text':
    case 'integer':
    case 'zip_code':
    case 'email_address':
      return simple_format($value);
    case 'phone_number':
      return click_phone_format($value);
    case 'select':
      if(!$value) return '';
      if(!is_object($value)) return $value;
      return simple_format($value->$field_info['display_field']);
    default:
      click_error("Unsupported type {$field_info['type']}");
  }
}


global $superlist_namespaces;
$superlist_namespaces = array();

function superlist_build_meta($meta)
{
  global $superlist_namespaces;
  if(!array_key_exists('human_name', $meta)) $meta['human_name']=humanize($meta['klass'],'_');
  $meta['params_name']=singularize(tableize($meta['klass']));

  if(!isset($meta['namespace'])) $meta['namespace'] = $meta['klass'];
  if(!isset($meta['builder'])) $meta['builder'] = null;
  if(array_search($meta['namespace'], $superlist_namespaces)!==FALSE)
  {
    click_error("Namespace '{$meta['namespace']}' already being used by a superlist on this page.");
  }
  $superlist_namespaces[] = $meta['namespace'];
  if(!isset($meta['objects'])) $meta['objects'] = eval("return {$meta['klass']}::find_all();");
  if(!isset($meta['ajax'])) $meta['ajax']=false;
  if(!isset($meta['new_breadcrumb'])) $meta['new_breadcrumb'] = false;
  if(!isset($meta['breadcrumb_name'])) $meta['breadcrumb_name'] = $meta['human_name'];

  if(!isset($meta['controls'])) $meta['controls'] = array();
  foreach(array('list', 'add', 'edit', 'delete') as $c) if(!isset($meta['controls'][$c])) $meta['controls'][$c] = true;

  if(!isset($meta['list']))
  {
    global $model_settings;
    $model_info = eval("return {$meta['klass']}::\$attribute_types;");
    $meta['list'] = array();
    foreach($model_info as $field_name=>$type_info)
    {
      if($field_name == 'id' || endswith($field_name, '_id')) continue;
      $meta['list'][] = $field_name;
    }
  }
  if(!is_array($meta['list'])) $meta['list'] = array($meta['list']);
  
  $ct = eval("return {$meta['klass']}::\$attribute_types;");
  $nl = array();
  foreach($meta['list'] as $k=>$v)
  {
    if(is_numeric($k))
    {
      $k = $v;
      $v = array();
    }
    
    if(!isset($v['type']) && !isset($ct[$k]['type']))
    {
      $v['type'] = 'text';
//      click_error("\$meta did not specify type information for $k and there is not enough information to infer a type from the data model.");
    }

    $nl[$k] = $v;
    
    if(isset($ct[$k]))
    {
      $nl[$k] = array_merge($nl[$k], $ct[$k]);
    }
    
    if(isset($nl[$k]['type']))
    {
      switch($nl[$k]['type'])
      {
        case 'text':
          $defaults = array('required'=>false);
          $nl[$k] = array_merge($nl[$k], $defaults);
          break;
        case 'select':
          $defaults = array('required'=>false, 'item_array'=>'available_'.pluralize($k), 'display_field'=>'name', 'value_field'=>'id');
          $nl[$k] = array_merge($nl[$k], $defaults);
          break;
        case 'mutex':
          $defaults = array('required'=>false, 'item_array'=>'available_'.$k, 'selected_item_array'=>$k, 'display_field'=>'name', 'value_field'=>'id');
          $nl[$k] = array_merge($nl[$k], $defaults);
          break;
      }
    }
  }
  
  $meta['list'] = $nl;  
  if(!isset($meta['sections']))
  {
    $meta['sections'] = array(
      'basic'=>$meta['list']
    );
  }
  
  if($meta['controls']['add'])
  {
    if(!isset($meta['new_object']))
    {
      $new_object = eval("return {$meta['klass']}::new_model_instance();");
      $meta['new_object'] = $new_object;
    }
    
    if(isset($meta['add']))
    {
      $meta['new_object']->superform_sections = $meta['add'];
    } else {
      if(!$meta['new_object']->superform_sections)
      {
        $meta['new_object']->superform_sections = $meta['sections'];
      }
    }
  }
  
  if($meta['controls']['edit'] && p("{$meta['namespace']}[oid]"))
  {
    if(!isset($meta['edit_object']))
    {
      $edit_object = eval("return {$meta['klass']}::find_by_id(p(\"{$meta['namespace']}[oid]\"));");
      $meta['edit_object'] = $edit_object;
    }
  
    if(isset($meta['edit']))
    {
      $meta['edit_object']->superform_sections = $meta['edit'];
    } else {
      if(!$meta['edit_object']->superform_sections)
      {
        $meta['edit_object']->superform_sections = $meta['sections'];
      }
    }
  }
  
  return $meta;
}
