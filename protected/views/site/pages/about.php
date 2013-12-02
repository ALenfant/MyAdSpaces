<?php
$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('app', 'About');
$this->breadcrumbs=array(
	Yii::t('app', 'About'),
);
?>
<h1><?php echo Yii::t('app', 'About'); ?></h1>

<p><?php echo Yii::t('app', '{name} is an ad network that enables you to pay in order to display ads on {clients}.', array('{name}' => Yii::app()->name, '{clients}' => Yii::app()->params['clients'])); ?></p>

<p><?php echo Yii::t('app', 'To check how it works, read our help page {here}.', array('{here}' => CHtml::link(Yii::t('app', 'here'), $this->createUrl('/site/page', array('view' => 'help'))))); ?></p>

<p><?php echo Yii::t('app', '{name} is powered by {software}.', array('{name}' => Yii::app()->name, '{software}' => CHtml::link('MyAdSpaces', 'http://www.myadspaces.net/', array('target' => '_blank')))); ?></p>