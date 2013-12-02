<?php
$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Campaign', 'url'=>array('index')),
	array('label'=>'Create Campaign', 'url'=>array('create')),
	array('label'=>'Update Campaign', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Campaign', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<h1>View Campaign #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'user_id',
		'time_created',
		'time_disabled',
		'title',
		'description',
		'link_url',
		'link_title',
		'textstyle_bold',
		'textstyle_underline',
		'textstyle_italic',
		'textstyle_marquee',
		'stats_views',
		'stats_clicks',
		'status',
	),
)); ?>
