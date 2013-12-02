<?php
Yii::app()->clientScript->registerCoreScript('jquery');

$this->breadcrumbs = array(
    'Campaigns' => array('manage'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Campaign', 'url' => array('index')),
    array('label' => 'Manage Campaign', 'url' => array('admin')),
);

echo '<h1>' . Yii::t('app', 'Create an ad campaign') . '</h1>';

switch ($page) {
    case 1: //Site list
        echo '<h2>' . Yii::t('app', 'Choose a website') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please choose a website on which you wish to display your advertisement') . '</em>';
        echo '<br/><br/>';

        foreach ($websites as $website) {
            echo '<strong>' . htmlspecialchars($website->name) . '</strong>' . '&nbsp;&ndash;&nbsp;';
            echo '<em>' . CHtml::link($website->url, $website->url, array('target' => '_blank', 'style' => 'opacity: 0.5')) . '</em><br/>';
            if (!empty($website->description))
                echo $website->description . '<br/>';
            echo CHtml::link(Yii::t('app', 'Create a new ad campaign for :name', array(':name' => $website->name)), array('campaign/create', 'page' => 2, 'website_id' => $website->id)) . '<br/>';
            echo '<br/>';
        }
        break;

    case 2:
        echo '<h2>' . Yii::t('app', 'Choose an advertisement space') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please choose an advertisement space on :name', array(':name' => $website->name)) . '</em>';
        echo '<br/><br/>';

        foreach ($adspaces as $adspace) {
            echo '<strong>' . htmlspecialchars($adspace->name) . '</strong>' . '&nbsp;&ndash;&nbsp;';
            echo '<em>' . CHtml::link($adspace->url, $adspace->url, array('target' => '_blank', 'style' => 'opacity: 0.5')) . '</em><br/>';
            if (!empty($adspace->description))
                echo $adspace->description . '<br/>';
            echo CHtml::link(Yii::t('app', 'Create a new ad campaign for :name', array(':name' => $adspace->name)), array('campaign/create', 'page' => 4, 'adspace_id' => $adspace->id)) . '<br/>';
            echo '<br/>';
        }
        break;

    case 4:
        ?>
        <h2><?php echo Yii::t('app', 'Create Campaign') ?></h2>

        <div class="form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'campaign-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data', //To allow file upload
                ),
                    ));

            //echo $form->errorSummary($model); // doesnt display all errors
            ?>

            <h2><?php echo Yii::t('app', 'Campaign details') ?></h2>

            <div class = "row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>

            <h2><?php echo Yii::t('app', 'Ad details') ?></h2>

            <?php
            $adtype->displayEditForm($form);
            ?>

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('app', 'Preview'), array('name' => 'preview')); ?>
                <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
            </div>

            <?php
            $this->endWidget();

            if ($preview) {
                $adtype->displayAd();
            }
            ?>
        </div><!-- form -->
        <?php
        break;
}
?>