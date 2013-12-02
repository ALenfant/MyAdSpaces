<?php
/* @var $this AdspaceController */
/* @var $model Adspace */

$this->breadcrumbs = array(
    Yii::t('app', 'Websites') => array('website/admin'),
    $website->name => array('website/view', 'id' => $website->id),
    Yii::t('app', 'Ad Spaces') => array('adspace/admin', 'website_id' => $website->id),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List Adspace', 'url' => array('index')),
    array('label' => 'Create Adspace', 'url' => array('create')),
    array('label' => 'View Adspace', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage Adspace', 'url' => array('admin')),
);
?>

<h1>Update Adspace <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'adtypes' => $adtypes)); ?>