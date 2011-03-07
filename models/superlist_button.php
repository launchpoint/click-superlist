<?

function superlist_button_get_calced_url__d($s)
{
  return $s->url_function ? $s->url_function : excerpt($s->url_code);
}

function superlist_button_calced_url($s, $params)
{
  if($s->url_function) return call_user_func($s->url_function.'_url');
  
  extract($params);
  $url = eval($s->url_code);
  return $url;
}