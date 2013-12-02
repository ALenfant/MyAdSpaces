<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'MyAdSpaces',
    'theme' => 'shadow_dancer',
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'clients' => 'My website', //List of clients displayed on the site
        // Payment providers configuration
        'paymentProviders' => array(
            'allopass' => array(
                'enabled' => true,
                'site_id' => -1, //Allopass site id
                'product_id' => -1, //Allopass product id
                'lang' => 'en', //Language of the Allopass buy button (en, fr, ...)
            //TODO:currency?
            ),
            'paypal' => array(
                'enabled' => true,
                'sandbox' => true, //Use the PayPal sandbox to test ?
                'account_email' => 'account@gmail.com',
                'pdt_token' => 'token',
                'lang' => 'en_US', //Payment button language (en_US, fr_FR...)
                'currency_code' => 'EUR', //Currency code
            ),
        ),
        //Email used for the contact page
        'senderEmail' => 'do-not-reply@myadspaces.net',
        //Global salt used to hash passwords
        'globalSalt' => 'Mange_du_poulet_pauvre_péon!',
    ),
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        /*//*
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),*/
    //*/
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            //'transportType' => 'php',
            'transportType' => 'smtp',
            'transportOptions' => array(),
            'viewPath' => 'application.views.email',
            'logging' => true,
            'dryRun' => false
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=myadspaces',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true //Profiles query (see log for details)
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'cache' => array(
            //Configure with a RAM cache for best performance
            //See http://www.yiiframework.com/doc/guide/1.1/en/caching.overview
            'class' => 'system.caching.CFileCache',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ),
                //Displays query profiling
                array(
                    'class' => 'CProfileLogRoute',
                    'levels' => 'error, warning, info',
                ),
            // uncomment the following to show log messages on web pages
            /* array(
              'class' => 'CWebLogRoute',
              ), */
            ),
        ),
    ),
);