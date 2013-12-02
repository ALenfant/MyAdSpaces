<?php
$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('app', 'Login');
$this->breadcrumbs = array(
    Yii::t('app', 'Login'),
);
?>

<h1><?php echo Yii::t('app', 'Login') ?></h1>

<p><?php echo Yii::t('app', 'Please fill out the following form with your login credentials') ?>:</p>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>

    <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.') ?></p>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
        <p class="hint">
            <?php echo Yii::t('app', 'The password associated with your account') ?>
        </p>
    </div>

    <div class="row rememberMe">
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
        <?php echo $form->label($model, 'rememberMe'); ?>
        <?php echo $form->error($model, 'rememberMe'); ?>
    </div>
    
    <div class="row">
        <?php echo CHtml::link(Yii::t('app', 'Lost password'), $this->createUrl('lostpassword')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('app','Login')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
