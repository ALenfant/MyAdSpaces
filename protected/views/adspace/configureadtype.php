<h2><?php echo Yii::t('app', 'Edit ad type configuration for ad space :adspace (:adtype)', array(':adspace' => $model->name, ':adtype' => $adtype->getDisplayName())) ?></h2>

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
    $adtypeconfig->displayEditForm($form);
    ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('app', 'Save')); ?>
    </div>

    <?php
    $this->endWidget();
    ?>
</div><!-- form -->