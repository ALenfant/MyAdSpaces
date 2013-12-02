<?php
/* @var $this AdspaceController */
/* @var $model Adspace */

$this->breadcrumbs = array(
    Yii::t('app', 'Websites') => array('website/admin'),
    $website->name => array('website/view', 'id' => $website->id),
    Yii::t('app', 'Ad Spaces') => array('adspace/admin', 'website_id' => $website->id),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Adspace', 'url' => array('index')),
    array('label' => 'Create Adspace', 'url' => array('create')),
    array('label' => 'Update Adspace', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Adspace', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Adspace', 'url' => array('admin')),
);
?>

<h1>View Adspace #<?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'website_id',
        'name',
        'description',
        'image_url',
        'type',
        'url',
        'enabled',
    ),
));
?>
