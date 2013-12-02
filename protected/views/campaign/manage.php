<?php
$this->breadcrumbs = array(
    'Campaigns'
);

$this->menu = array(
    array('label' => 'List Campaign', 'url' => array('index')),
    array('label' => 'Create Campaign', 'url' => array('create')),
);
?>

<h1><?php echo Yii::t('app', 'Manage Campaigns'); ?></h1>
<em><?php echo Yii::t('app', 'You can view all your campaigns and manage them on this page. To run a campaign, go to its details page.'); ?></em>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'campaign-grid',
    'dataProvider' => $model->detailsList(Yii::app()->user->id),
    'filter' => $model,
    'columns' => array(
        'name',
        'time_created',
        'time_updated',
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}{update}',
            'buttons' => array(
                'view' => array(
                    'label' => Yii::t('app', 'Details'),
                    'url' => '$this->grid->controller->createUrl("details", array("id"=>$data->primaryKey))',
                )
            )
        ),
    ),
));
?>

<h2><?php echo Yii::t('app', 'Actions') ?></h2>
<?php echo CHtml::link(Yii::t('app', 'Create a new campaign'), $this->createUrl('campaign/create')) ?><br/>
