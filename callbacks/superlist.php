<?
$cmd = p("{$meta['namespace']}[cmd]",'list');
if(!isset($meta['controls'][$cmd]) || $meta['controls'][$cmd] !== 'custom')
{
  if ($meta['new_breadcrumb']) echo begin_breadcrumb();
  event("superlist_$cmd", $meta);
  if ($meta['new_breadcrumb']) echo end_breadcrumb();
}
