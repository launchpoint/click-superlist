<?

if(p("{$namespace}[cmd]")!='edit') return;

if(p($params_name) && is_postback() && p('superform_cmd'))
{
  switch($params['superform_cmd'])
  {
    case 'refresh':
      $edit_object->update_attributes($params[$params_name],false);
      break;
  }
}

event("superlist_{$params_name}_form_sections", array('object'=>$edit_object));

if(p($params_name) && is_postback() && p('superform_cmd'))
{
  switch($params['superform_cmd'])
  {
    case 'refresh':
      break;
    case 'commit':
      $edit_object->update_attributes($params[$params_name]);
      if($edit_object->is_valid)
      {
        flash_next("$human_name saved.");
        redirect_to(command_url('list', array(), $namespace));
      }
      break;
    default:
      click_error("Unrecognized superform mode: {$params['superform_cmd']}.");
  }
}

add_breadcrumb($breadcrumb_name, command_url('list', array(), $namespace));
add_breadcrumb("Edit {$edit_object->name}", command_url('edit', array('oid'=>$edit_object->id), $namespace));
event($params_name."_before_addedit", array($params_name=>$edit_object));
$edit_object->superform();
event($params_name."_after_addedit", array($params_name=>$edit_object));
event($params_name."_after_edit", array($params_name=>$edit_object));
