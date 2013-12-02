<?php
$this->breadcrumbs=array(
	'Campaigns',
);

$this->menu=array(
	array('label'=>'Create Campaign', 'url'=>array('create')),
	array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<h1>Campaigns</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
