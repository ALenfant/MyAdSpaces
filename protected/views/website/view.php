<?php
/* @var $this WebsiteController */
/* @var $model Website */

$this->breadcrumbs=array(
	'Websites'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Website', 'url'=>array('index')),
	array('label'=>'Create Website', 'url'=>array('create')),
	array('label'=>'Update Website', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Website', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Website', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app', 'View Website : :name', array(':name' => $model->name))?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'image_url',
		'url',
		'enabled',
	),
));
?>

<h2><?php echo Yii::t('app', 'Actions') ?></h2>
<?php echo CHtml::link(Yii::t('app', 'View campaigns for this website'), $this->createUrl('campaign/admin', array('website_id' => $model->id))) ?><br/>
<?php echo CHtml::link(Yii::t('app', 'View ad spaces for this website'), $this->createUrl('adspace/admin', array('website_id' => $model->id))) ?><br/>