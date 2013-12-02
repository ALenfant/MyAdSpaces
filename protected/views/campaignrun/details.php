<?php
/* @var $this CampaignrunController */
/* @var $model Campaignrun */

$this->breadcrumbs = array(
    'Campaigns' => array('campaign/manage'),
    $campaign->name => array('campaign/details', 'id' => $campaign->id),
    'Run #' . $model->id,
);

//Register Javascript necessary for the graphs
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/jquery.jqplot.min.js');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
$cs->registerScriptFile(Yii::app()->getBaseUrl() . '/js/jqplot/plugins/jqplot.highlighter.min.js');
$cs->registerCssFile(Yii::app()->getBaseUrl() . '/js/jqplot/jquery.jqplot.min.css');
?>

<h1>View Campaignrun #<?php echo $model->id; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'campaign_id',
        'adspace_id',
        'paytype',
        'paytype_amount',
        'time_created',
        'time_enabled',
        'time_disabled',
        'stats_views',
        'stats_clicks',
        'status',
    ),
));
?>

<h2><?php echo Yii::t('app', 'Statistics') ?></h2>
<em><?php echo Yii::t('app', 'You can access detailed stats per run by viewing their details.'); ?></em>

<div align="center">
    <div id="chart2" style="height:400px; width:100%; background-image:url('images/ajax-loading.gif'); background-repeat:no-repeat; background-position:center;"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var dataUrl = "<?php echo $this->createUrl('stats/graph', array('campaignrun_id' => $model->id)); ?>";
        
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
