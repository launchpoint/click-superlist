<?

$s = SuperlistSectionField::find_by_id($params['SuperlistSectionField']['oid']);

$queries = StoredQuery::find_all( array(
  'order'=>'name',
));

$meta = array(
  'klass'=>'StoredQuery',
  'objects'=>$queries,
  'list'=>array('name'),
  'sections'=>array(
    'basic'=>array(
      'name',
      'code',
    )
  ),
);

superlist($meta);
