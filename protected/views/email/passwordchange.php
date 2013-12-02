<h2><?php echo yii::t('app', 'Password change'); ?></h2>
<?php echo yii::t('app', 'You asked us to change your password for your account {username} on {sitename}.', array('{username}' => $username,'{sitename}' => Yii::app()->name)); ?><br/>
<?php echo yii::t('app', 'To proceed, please click the link below'); ?><br/>
<?php echo CHtml::link(Yii::t('app', 'Change my password'), $link); ?><br/>
<?php echo yii::t('app', 'If this is an error and you did not request a password change, please ignore this e-mail.'); ?><br/>
<?php echo yii::t('app', 'Thank you,'); ?><br/>
<?php echo yii::t('app', 'The {sitename} team', array('{sitename}' => Yii::app()->name)); ?>