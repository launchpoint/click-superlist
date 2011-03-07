<?

foreach($models as $model_klass)
{
  $t = singularize(tableize($model_klass));
  $table_name = eval("return $model_klass::\$table_name;");
  $code = <<<PHP

function {$t}_superlist_for(\$obj, \$collection, \$meta=array())
{
  codegen_superlist(\$obj, \$collection, \$meta);
}

PHP;
  $codegen[] = $code;
}

$queries = StoredQuery::find_all();
foreach($queries as $q)
{
  $code =<<<PHP
function {$q->function_name}()
{
  {$q->code};
}
PHP;
  $codegen[] = $code;
}

$superlists = Superlist::find_all( array(
  'conditions'=>array('length(name_function_body)>0'),
));
foreach($superlists as $s)
{
  if(!$s->name_function_body) continue;
  $t = singularize(tableize($s->class));
  $code = <<<PHP
function {$t}_get_name__d(\$obj)
{
  {$s->name_function_body};
}
PHP;
  $codegen[] = $code;
  
}
