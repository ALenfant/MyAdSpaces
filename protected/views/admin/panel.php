<?php
/* @var $this AdminController */

$this->breadcrumbs = array(
    Yii::t('app', 'Admin Panel'),
);
?>
<h1><?php echo Yii::t('app', 'Admin Panel') ?></h1>
<?php echo Yii::t('app', 'Hello, <strong>:username</strong>! The server time is currently <strong>:servertime</strong>.', array(':username' => Yii::app()->user->name, ':servertime' => date('Y-m-d H:i:s'))) ?><br/>
<?php echo Yii::t('app', 'Welcome to the administration panel, where you can manage your websites, current ad campaigns and view statistics.') ?>
<br/>
<br/>
<table border="0">
    <tr>
        <td style="vertical-align:top">
            <h2><?php echo Yii::t('app', 'Websites') ?></h2>
            <ul>
                <li><?php echo CHtml::link(Yii::t('app', 'Manage Websites'), $this->createUrl('website/admin')) ?></li>
                <li>Two</li>
            </ul>
        </td><td style="vertical-align:top">
            <h2><?php echo Yii::t('app', 'Ad Campaigns') ?></h2>
        </td>
    </tr>
</table>