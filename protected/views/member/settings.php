<h1><?php echo Yii::t('app', 'Settings'); ?></h1>
<fieldset>
    <h2><?php echo Yii::t('app', 'Password') ?></h2>
    <div class="form">
        <?php echo CHtml::form(); ?>

        <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.') ?></p>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'Current password') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::passwordField('password') ?>
        </div>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'New password') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::passwordField('newpassword') ?>
        </div>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'Repeat new password') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::passwordField('newpassword2') ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('app', 'Change password'),array('name'=>'passwordform')); ?>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</fieldset>
<fieldset>
    <h2><?php echo Yii::t('app', 'E-mail') ?></h2>
    <div class="form">
        <?php echo CHtml::form(); ?>

        <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.') ?></p>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'Current e-mail') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::textField('email') ?>
        </div>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'New e-mail') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::textField('newemail') ?>
        </div>

        <div class="row">
            <?php echo CHtml::label(Yii::t('app', 'Repeat new e-mail') . ' <span class="required">*</span>', 'email') ?>
            <?php echo CHtml::textField('newemail2') ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('app', 'Change e-mail'),array('name'=>'emailform')); ?>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</fieldset>