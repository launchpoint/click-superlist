<?

function stored_query_get_function_name__d($s)
{
  $name = preg_replace("/\s/", '_', $s->name);
  $name = strtolower($name);
  return "query_".$name;
}