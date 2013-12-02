<?php
/* @var $this WebsiteController */
/* @var $model Website */

$this->breadcrumbs=array(
	'Websites'=>array('admin'),
	'Add',
);

$this->menu=array(
	array('label'=>'List Website', 'url'=>array('index')),
	array('label'=>'Manage Website', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app', 'Add Website') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>