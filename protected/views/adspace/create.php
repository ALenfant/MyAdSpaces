<?php
/* @var $this AdspaceController */
/* @var $model Adspace */

$this->breadcrumbs = array(
    Yii::t('app', 'Websites') => array('website/admin'),
    $website->name => array('website/view', 'id'=>$website->id),
    Yii::t('app', 'Ad Spaces') => array('adspace/admin', 'website_id'=>$website->id),
    Yii::t('app', 'Create'),
);

$this->menu=array(
	array('label'=>'List Adspace', 'url'=>array('index')),
	array('label'=>'Manage Adspace', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app', 'Create Ad Space for :name', array(':name' => $website->name)) ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'adtypes' => $adtypes)); ?>