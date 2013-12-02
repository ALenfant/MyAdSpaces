<?php
/* @var $this AdspaceController */
/* @var $model Adspace */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'adspace-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.') ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php echo $form->textField($model,'image_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>

        <?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textArea($model,'type',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>
         */ ?>
        <div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',$adtypes);?>
                <em><?php echo Yii::t('app', 'If possible, you will be able to configure this ad type at the next step') ?></em>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'fallback_html'); ?>
                <em><?php echo Yii::t('app', 'This HTML code will be sent if there are no campaigns to display in this ad space'); ?></em><br/>
		<?php echo $form->textArea($model,'fallback_html', array('rows' => 6, 'cols' => 60)); ?>
		<?php echo $form->error($model,'fallback_html'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'paybyclick_enabled'); ?>
		<?php echo $form->checkBox($model, 'paybyclick_enabled', array('value'=>'1', 'uncheckValue'=>'0'));; ?>
                <?php echo Yii::t('app', 'Price per click'); ?>
            	<?php echo $form->textField($model,'paybyclick_price'); ?>
                <?php echo Yii::t('app', 'Minimum clicks'); ?>
            	<?php echo $form->textField($model,'paybyclick_minimum'); ?>
		<?php echo $form->error($model,'paybyclick_enabled'); ?>
                <?php echo $form->error($model,'paybyclick_price'); ?>
                <?php echo $form->error($model,'paybyclick_minimum'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'paybyview_enabled'); ?>
		<?php echo $form->checkBox($model, 'paybyview_enabled', array('value'=>'1', 'uncheckValue'=>'0'));; ?>
                <?php echo Yii::t('app', 'Price per 1000 views'); ?>
            	<?php echo $form->textField($model,'paybyclick_price'); ?>
                <?php echo Yii::t('app', 'Minimum views'); ?>
            	<?php echo $form->textField($model,'paybyclick_minimum'); ?>
		<?php echo $form->error($model,'paybyview_enabled'); ?>
                <?php echo $form->error($model,'paybyview_price'); ?>
                <?php echo $form->error($model,'paybyview_minimum'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'paybyduration_enabled'); ?>
		<?php echo $form->checkBox($model, 'paybyduration_enabled', array('value'=>'1', 'uncheckValue'=>'0'));; ?>
                <?php echo Yii::t('app', 'Price per day'); ?>
            	<?php echo $form->textField($model,'paybyclick_price'); ?>
                <?php echo Yii::t('app', 'Minimum days'); ?>
            	<?php echo $form->textField($model,'paybyclick_minimum'); ?>
		<?php echo $form->error($model,'paybyduration_enabled'); ?>
                <?php echo $form->error($model,'paybyduration_price'); ?>
                <?php echo $form->error($model,'paybyduration_minimum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->checkBox($model, 'enabled', array('value'=>'1', 'uncheckValue'=>'0'));; ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->