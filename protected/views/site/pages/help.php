<?php
$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('app', 'Help');
$this->breadcrumbs = array(
    Yii::t('app', 'Help'),
);
?>
<h1><?php echo Yii::t('app', 'Help'); ?></h1>

<p><?php echo Yii::t('app', 'To use {name} in order to display ads on {clients}, you first have to create an account on our system.', array('{name}' => Yii::app()->name, '{clients}' => Yii::app()->params['clients'])); ?></p>

<p><?php echo Yii::t('app', 'You can then be able to proceed with the creaton of an ad campaign, during which you will choose the website and ad space on which you want it to be displayed. You will be able to use different formats depending on the ad space you chose, such as banners for example.'); ?></p>

<p><?php echo Yii::t('app', 'After your ad campaign is created, to display it you will be able to run it on the ad space you previously chose. If you want, you can then run the same campaign on additional ad spaces, if they accept the exact same format.'); ?></p>

<p><?php echo Yii::t('app', 'The prices depend on the chosen ad space where you want to display an ad campaign, and also the number of views, clicks or days you want to purchase (available depending on each ad space).'); ?></p>

<p><?php echo Yii::t('app', 'You can fund your account using various payment methods such as PayPal (credit card and account) and Allopass (micropayment via SMS and call).'); ?></p>

<p><?php echo Yii::t('app', 'You will have access to detailed statistics for both views and clicks for each ad campaign run.'); ?></p>

<p><?php echo Yii::t('app', 'If your question hasn\'t been answered, please don\'t hesitate to contact us using the Contact page.'); ?></p>