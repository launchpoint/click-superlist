<?

if(p("{$namespace}[cmd]")!='delete') return;

if(p("{$namespace}[c]"))
{
  $edit_object->delete();
  flash_next("{$edit_object->name} deleted.");
  redirect_to(command_url('list', array('oid'=>0), $namespace));
}