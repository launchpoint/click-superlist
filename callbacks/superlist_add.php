<?

if(p("{$namespace}[cmd]")!='add') return;

event("superlist_{$params_name}_add_clicked", array('object'=>$new_object));

if(is_postback() && p('superform_cmd'))
{
  switch($params['superform_cmd'])
  {
    case 'refresh':
      $new_object->update_attributes($params[$params_name],false);
      break;
  }
}

event("superlist_{$params_name}_form_sections", array('object'=>$new_object));

if(is_postback() && p('superform_cmd') && p($params_name))
{
  switch($params['superform_cmd'])
  {
    case 'refresh':
      break;
    case 'commit':
      $new_object->update_attributes($params[$params_name]);
      if($new_object->is_valid)
      {
        flash_next("New $human_name added.");
        redirect_to(command_url('list', array(), $namespace));
      }
      break;
    default:
      click_error("Unrecognized superform mode: {$params['superform_cmd']}.");
  }
}

add_breadcrumb($breadcrumb_name, command_url('list', array(), $namespace));
add_breadcrumb("Add $human_name", command_url('add', array(), $namespace));
event('superlist_'.$params_name."_before_addedit", array('object'=>$new_object));
$new_object->superform();
event($params_name."_after_addedit", array($params_name=>$new_object));
