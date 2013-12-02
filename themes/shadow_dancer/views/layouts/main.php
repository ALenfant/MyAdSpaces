<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/buttons.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/icons.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/tables.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/mbmenu.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/mbmenu_iestyles.css" />


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page">
            <div id="topnav"><div class="topnav_text">
                    <?php if (Yii::app()->user->isGuest) { ?>
                        <a href = "<?php echo CHtml::normalizeUrl(array('site/login')) ?>">Login</a> |
                        <a href = "<?php echo CHtml::normalizeUrl(array('site/register')) ?>">Register</a> |
                    <?php } else { ?>
                        <?php if (Yii::app()->user->role === 'admin') { ?>
                            <a href = "<?php echo CHtml::normalizeUrl(array('admin/panel')) ?>">Panel</a> |
                        <?php } else { ?>
                            <a href = "<?php echo CHtml::normalizeUrl(array('member/panel')) ?>">Panel</a> |
                            <a href = "<?php echo CHtml::normalizeUrl(array('member/settings')) ?>">Settings</a> |
                        <?php } ?>
                        <a href = "<?php echo CHtml::normalizeUrl(array('site/logout')) ?>">Logout (<?php echo Yii::app()->user->name ?>)</a>
                    <?php } ?>
                </div></div>
            <div id="header">
                <?php if (!Yii::app()->user->isGuest && Yii::app()->user->role !== 'admin') { ?>
                    <div style="float:right; margin-top: 6px; margin-right: 4px;">
                        <strong><?php echo Yii::t('app', 'Account balance'); ?> :</strong><br/>
                        <?php
                        echo Yii::t('app', ':balance €', array(':balance' => User::model()->findByPk(Yii::app()->user->id)->balance));
                        echo ' ' . CHtml::link('' . Yii::t('app', 'Add money') . '', $this->createUrl('member/fund'));
                        ?>
                    </div>
                <?php } ?>
                <div id="logo">
                    <!--<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png"></img><?php //echo CHtml::encode(Yii::app()->name);                         ?>-->
                    <?php echo CHtml::encode(Yii::app()->name); ?><sup style="font-size: 10px;">BETA</sup>
                </div>
            </div><!-- header -->
            <!--
            <?php /* $this->widget('application.extensions.mbmenu.MbMenu',array(
              'items'=>array(
              array('label'=>'Dashboard', 'url'=>array('/site/index'),'itemOptions'=>array('class'=>'test')),
              array('label'=>'Theme Pages',
              'items'=>array(
              array('label'=>'Graphs & Charts', 'url'=>array('/site/page', 'view'=>'graphs'),'itemOptions'=>array('class'=>'icon_chart')),
              array('label'=>'Form Elements', 'url'=>array('/site/page', 'view'=>'forms')),
              array('label'=>'Interface Elements', 'url'=>array('/site/page', 'view'=>'interface')),
              array('label'=>'Error Pages', 'url'=>array('/site/page', 'view'=>'Demo 404 page')),
              array('label'=>'Calendar', 'url'=>array('/site/page', 'view'=>'calendar')),
              array('label'=>'Buttons & Icons', 'url'=>array('/site/page', 'view'=>'buttons_and_icons')),
              ),
              ),
              array('label'=>'Gii Generated Module',
              'items'=>array(
              array('label'=>'Items', 'url'=>array('/theme/index')),
              array('label'=>'Create Item', 'url'=>array('/theme/create')),
              array('label'=>'Manage Items', 'url'=>array('/theme/admin')),
              ),
              ),
              array('label'=>'Contact', 'url'=>array('/site/contact')),
              array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
              array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
              ),
              )); */ ?> --->
            <div id="mainmenu">

                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        //array('label' => Yii::t('app', 'Home'), 'url' => array('/site/index')),
                        array('label' => Yii::t('app', 'Login'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => Yii::t('app', 'Register'), 'url' => array('/site/register'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => Yii::t('app', 'Panel'), 'url' => array('/member/panel'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->role !== 'admin')),
                        array('label' => Yii::t('app', 'Panel'), 'url' => array('/admin/panel'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->role === 'admin')),
                        array('label' => Yii::t('app', 'Help'), 'url' => array('/site/page', 'view' => 'help')),
                        array('label' => Yii::t('app', 'About'), 'url' => array('/site/page', 'view' => 'about')),
                        array('label' => Yii::t('app', 'Contact'), 'url' => array('/site/contact')),
                    //array('label' => Yii::t('app', 'Logout') . ' (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                    /*
                      array('label'=>'Dashboard', 'url'=>array('/site/index')),
                      array('label'=>'Graphs', 'url'=>array('/site/page', 'view'=>'graphs'),'itemOptions'=>array('class'=>'icon_chart')),
                      array('label'=>'Form', 'url'=>array('/site/page', 'view'=>'forms')),
                      array('label'=>'Interface', 'url'=>array('/site/page', 'view'=>'interface')),
                      array('label'=>'Buttons & Icons', 'url'=>array('/site/page', 'view'=>'buttons_and_icons')),
                      array('label'=>'Error Pages', 'url'=>array('/site/page', 'view'=>'Demo 404 page')),
                     */
                    ),
                ));
                ?>
            </div> <!--mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>

            <?php
            //Flash messages
            foreach (Yii::app()->user->getFlashes() as $key => $message) {
                echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
            }
            ?>

            <?php echo $content; ?>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> Antonin Lenfant<br/>
                All Rights Reserved.<br/>
                <?php echo Yii::t('app', 'Powered by {software}', array('{software}' => CHtml::link('MyAdSpaces', 'http://www.myadspaces.net/', array('target' => '_blank')))); //Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>