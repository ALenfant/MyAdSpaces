<?php
/* @var $this AdspaceController */
/* @var $model Adspace */

$this->breadcrumbs = array(
    Yii::t('app', 'Websites') => array('website/admin'),
    $website->name => array('website/view', 'id'=>$website->id),
    Yii::t('app', 'Ad Spaces'),
);

$this->menu = array(
    array('label' => 'List Adspace', 'url' => array('index')),
    array('label' => 'Create Adspace', 'url' => array('create')),
);
?>

<h1><?php echo Yii::t('app', 'Manage Ad Spaces for :name', array(':name' => $website->name)) ?></h1>

<p>
    <?php echo Yii::t('app', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.') ?>
</p>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'adspace-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        'description',
        'image_url',
        'type',
        /*
          'url',
          'enabled',
         */
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
<?php echo CHtml::link(Yii::t('app', 'Create a new ad space for :name', array(':name' => $website->name)), $this->createUrl('adspace/create', array('website_id' => $website->id))) ?><br/>