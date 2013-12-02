<?php
$this->breadcrumbs = array(
    'Campaigns' => array('manage'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Campaign', 'url' => array('index')),
    array('label' => 'Create Campaign', 'url' => array('create')),
    array('label' => 'Update Campaign', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Campaign', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Campaign', 'url' => array('admin')),
);

//Register Javascript necessary for the graphs
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/jquery.jqplot.min.js');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/plugins/jqplot.highlighter.min.js');
$cs->registerCssFile(Yii::app()->getBaseUrl() . '/js/jqplot/jquery.jqplot.min.css');
?>

<h1><?php echo Yii::t('app', 'View Campaign : :name', array(':name' => $model->name)); ?></h1>

<h2><?php echo Yii::t('app', 'Preview') ?></h2>
<?php
$adtype = AdTypes::getTypeClass($model->type)->loadParameters($model->parameters); //We get the ad type
$adtype->getConfig(); //->loadConfig($adspace->type_config); //We load the default config (campaign doesn't depend on an adspace)
echo $adtype->displayAd();
?>

<h2><?php echo Yii::t('app', 'Runs') ?></h2>
<em>This section details the times this campaign has already been run</em>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'campaign-grid',
    'dataProvider' => $campaignrun->detailsList($model->id),
    'filter' => $campaignrun,
    'columns' => array(
        'id',
        'time_created',
        'time_disabled',
        'adspace_id',
        'status',
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}{update}',
            'buttons' => array(
                'view' => array
                    (
                    'label' => Yii::t('app', 'Details and stats'),
                    'url' => 'Yii::app()->createUrl("campaignrun/details", array("id"=>$data->id))',
                ),
            )
        ),
    ),
));
?>

<h2><?php echo Yii::t('app', 'Actions') ?></h2>
<?php echo CHtml::link(Yii::t('app', 'Run this campaign'), $this->createUrl('campaign/run', array('campaign_id' => $model->id))) ?><br/>
<?php echo CHtml::link(Yii::t('app', 'Edit this campaign'), $this->createUrl('', array('id' => $model->id))) ?><br/>
<?php //echo CHtml::link(Yii::t('app', 'Remove this campaign'), $this->createUrl('', array('id' => $model->id))) ?><br/>

<h2><?php echo Yii::t('app', 'Statistics') ?></h2>
<em><?php echo Yii::t('app', 'You can access detailed stats per run by viewing their details.'); ?></em>

<div align="center">
    <div id="chart2" style="height:400px; width:100%; background-image:url('images/ajax-loading.gif'); background-repeat:no-repeat; background-position:center;"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var dataUrl = "<?php echo $this->createUrl('stats/graph', array('campaign_id' => $model->id)); ?>";
        
        $.ajax({
            url:dataUrl,
            async:true,
            dataType:'json',
            success:function(ddata) {
                if(ddata.length == 0 || ddata[0].length == 0) {
                    $('#chart2').html("<br/><i><?php echo Yii::t('app', 'No data available') ?></i>")
                    $('#chart2').css("background-image", "");  
                } else {
                    $.jqplot('chart2', ddata,{
                        //title: "AJAX JSON Data Renderer"
                        animate: true,
                        axes:{
                            xaxis:{
                                renderer:$.jqplot.DateAxisRenderer//, 
                                //tickOptions:{formatString:'%b %#d, %#I %p'},
                                //min:'June 16, 2008 8:00AM', 
                                //tickInterval:'2 weeks'
                            },
                        },
                        series:{
                            series1:{
                                label:'lol'
                            },
                            series2:{
                                label:'mdr'
                            }
                        },
                        highlighter: {
                            show: true,
                            sizeAdjust: 1,
                            tooltipOffset: 9,
                            //useAxesFormatters: true,
                            tooltipAxes: 'both',
                            yvalues: 1,
                            formatString: '%s : %d'
                        },
                        seriesDefaults: {
                            rendererOptions: {
                                smooth: true,
                                animation: {
                                    show: true
                                }
                            }
                        }
                    });
                }
            },
            error:function(jqXHR, textStatus, errorThrown){alert('Error loading AJAX graph data. Please contact the administrator.' + "\n" + textStatus + "\n" + errorThrown )}
        });

        
    });
</script>