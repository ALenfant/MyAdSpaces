<h2><?php echo yii::t('app', 'Lost password'); ?></h2>
<?php echo yii::t('app', 'You asked us to reset your password for your account {username} on {sitename}.', array('{username}' => $username,'{sitename}' => Yii::app()->name)); ?><br/>
<?php echo yii::t('app', 'To proceed, please click the link below'); ?><br/>
<?php echo yii::t('app', 'Your new password will be : {password}', array('{password}' => $password)); ?><br/>
<?php echo CHtml::link(Yii::t('app', 'Reset my password'), $link); ?><br/>
<?php echo yii::t('app', 'If this is an error and you did not request a password reset, please ignore this e-mail.'); ?><br/>
<?php echo yii::t('app', 'Thank you,'); ?><br/>
<?php echo yii::t('app', 'The {sitename} team', array('{sitename}' => Yii::app()->name)); ?>