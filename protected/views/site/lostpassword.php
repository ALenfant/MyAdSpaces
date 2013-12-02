<h1><?php echo Yii::t('app', 'Lost password') ?></h1>

<?php
switch ($page) {
    case 1:
        ?>
        <p><?php echo Yii::t('app', 'Please fill out the following form with your login credentials') ?>:</p>

        <div class="form">
            <?php
            echo CHtml::beginForm();
            ?>

            <p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.') ?></p>

            <div class="row">
                <?php echo CHtml::label(Yii::t('app', 'E-mail') . ' <span class="required">*</span>', 'email') ?>
                <?php echo CHtml::textField('email') ?>
            </div>

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('app', 'Submit')); ?>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div><!-- form -->
        <?php
        break;
    
    case 2:
        echo Yii::t('app', 'An e-mail with your new password and a link has been sent to your e-mail address. Please open this e-mail and click on the link to finalize the password change.');
        break;
    
    case 3:
        echo Yii::t('app', 'The password change was successful. Please login with your account and the new password written in the e-mail, you can then change it in your member panel.');
        break;
}
?>