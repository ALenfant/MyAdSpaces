<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');
$this->breadcrumbs = array(
    Yii::t('app', 'Panel'),
);
?>
<h1><?php echo Yii::t('app', 'Panel') ?></h1>
<?php if (User::model()->findByPk(Yii::app()->user->id)->balance < 0.01) { ?>
    <div class="flash-notice">
        <?php echo Yii::t('app', 'You don\'t have any funds on your account.'); ?>
    </div>
<?php } ?>
<?php echo Yii::t('app', 'Hello, <strong>:username</strong>! The server time is currently <strong>:servertime</strong>.', array(':username' => Yii::app()->user->name, ':servertime' => date('Y-m-d H:i:s'))) ?><br/>
<?php echo Yii::t('app', 'Welcome to your panel, where you can manage your existing ad campaigns or create a new one.') ?><br/>
<br/>
<div class="span-23 showgrid">
    <div class="dashboardIcons span-17">
        <div class="dashIcon span-4">
            <a href="<?php echo $this->createUrl('campaign/create') ?>">
                <img src="images/panel_new.png" title="<?php echo Yii::t('app', 'New campaign') ?>" /><br/>
                <div class="dashIconText "><?php echo Yii::t('app', 'New campaign') ?></div>
            </a>
        </div>

        <div class="dashIcon span-4">
            <a href="<?php echo $this->createUrl('campaign/manage') ?>">
                <img src="images/panel_manage.png" title="<?php echo Yii::t('app', 'Manage Campaigns') ?>" /><br/>
                <div class="dashIconText "><?php echo Yii::t('app', 'Manage Campaigns') ?></div>
            </a>
        </div>

        <div class="dashIcon span-4">
            <a href="<?php echo $this->createUrl('member/settings') ?>">
                <img src="images/panel_settings.png" title="<?php echo Yii::t('app', 'Settings') ?>" /><br/>
                <div class="dashIconText"><?php echo Yii::t('app', 'Settings') ?></div>
            </a>
        </div>

        <div class="dashIcon span-4">
            <a href="<?php echo $this->createUrl('/site/page', array('view' => 'help')) ?>">
                <img src="images/panel_help.png" title="<?php echo Yii::t('app', 'Help') ?>" /><br/>
                <div class="dashIconText"><?php echo Yii::t('app', 'Help') ?></div>
            </a>
        </div>
    </div><!-- END OF .dashIcons -->
    <div class="span-6 last">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => Yii::t('app', 'Account Balance'),
        ));
        echo '<div align="center">';
        echo '<img src="images/wallet.png" width="33%" title="' . Yii::t('app', 'Wallet') . '" /><br/>';
        echo Yii::t('app', 'You currently have <strong>:balance €</strong> in your account you can use on our services.', array(':balance' => User::model()->findByPk(Yii::app()->user->id)->balance));
        echo '<br/>';
        echo CHtml::link(Yii::t('app', 'Click here to fund your account'), $this->createUrl('member/fund'));
        echo '</div>';
        $this->endWidget();
        ?>
    </div>
</div>
<?php /*
<table border="0">
    <tr>
        <td style="text-align:center">
            <a href="<?php echo $this->createUrl('campaign/create') ?>">
                <img src="images/panel_new.png" title="<?php echo Yii::t('app', 'New campaign') ?>" /><br/>
                <strong><?php echo Yii::t('app', 'New campaign') ?></strong><br/>
            </a>
            <?php echo Yii::t('app', 'Create a new ad campaign') ?>
        </td>
        <td style="text-align:center">
            <a href="<?php echo $this->createUrl('campaign/manage') ?>">
                <img src="images/panel_manage.png" title="<?php echo Yii::t('app', 'Manage campaigns') ?>" /><br/>
                <strong><?php echo Yii::t('app', 'Manage campaigns') ?></strong><br/>
            </a>
            <?php echo Yii::t('app', 'Manage your ad campaigns') ?>
        </td>
        <td style="text-align:center">
            <a href="<?php echo $this->createUrl('member/settings') ?>">
                <img src="images/panel_settings.png" title="<?php echo Yii::t('app', 'Settings') ?>" /><br/>
                <strong><?php echo Yii::t('app', 'Settings') ?></strong><br/>
            </a>
            <?php echo Yii::t('app', 'Change your account settings') ?>
        </td>
        <td style="text-align:center">
            <a href="<?php echo $this->createUrl('/site/page', array('view' => 'help')) ?>">
                <img src="images/panel_help.png" title="<?php echo Yii::t('app', 'Help') ?>" /><br/>
                <strong><?php echo Yii::t('app', 'Help') ?></strong><br/>
            </a>
            <?php echo Yii::t('app', 'Check our help page') ?>
        </td>
    </tr>
</table>*/