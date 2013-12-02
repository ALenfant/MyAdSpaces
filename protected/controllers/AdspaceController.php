<?php

class AdspaceController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow only admins
                'users' => array('admin'),
            ),
            array('allow', // allow everybody to display or click the ads
                'actions' => array('display', 'click'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);

        $this->render('view', array(
            'model' => $model,
            'website' => Website::model()->findByPk($model->website_id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($website_id) {
        $model = new Adspace;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $website = Website::model()->findByPk($website_id);

        if (isset($_POST['Adspace'])) {
            $model->attributes = $_POST['Adspace'];
            $model->website_id = $website_id;
            if ($model->save())
            //$this->redirect(array('view', 'id' => $model->id, 'website_id' => $website_id));
                $this->redirect(array('configureadtype', 'id' => $model->id, 'website_id' => $website_id));
        }

        $adtypes = AdTypes::getList();
        $adtypeslist = Array();
        foreach ($adtypes as $adtype) {
            $adtypeslist[get_class($adtype)] = $adtype->getDisplayName();
        }

        $this->render('create', array(
            'model' => $model,
            'adtypes' => $adtypeslist,
            'website' => $website,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Adspace'])) {
            $model->attributes = $_POST['Adspace'];
            if ($model->save()) {
                Yii::import('ext.flushable.FlushableDependency'); //Extension to flush the cache when needed
                FlushableDependency::flushItem($id, 'Adspace'); //We flush the cached data to force a reload!
                //$this->redirect(array('view', 'id' => $model->id));
                $this->redirect(array('configureadtype', 'id' => $model->id, 'website_id' => $model->website_id));
            }
        }

        $adtypes = AdTypes::getList();
        $adtypeslist = Array();
        foreach ($adtypes as $adtype) {
            $adtypeslist[get_class($adtype)] = $adtype->getDisplayName();
        }

        $this->render('update', array(
            'model' => $model,
            'adtypes' => $adtypeslist,
            'website' => Website::model()->findByPk($model->website_id),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin($website_id) {
        $website = Website::model()->findByPk($website_id);

        $model = new Adspace('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Adspace']))
            $model->attributes = $_GET['Adspace'];

        $this->render('admin', array(
            'model' => $model,
            'website' => $website,
        ));
    }

    //TODO: Find a cache duration for 2 methods below

    /**
     * Displays the ad space with the selected id
     * @param type $id The adspace to display
     */
    public function actionDisplay($id) {
        Yii::import('ext.flushable.FlushableDependency'); //Extension to flush the cache when needed
        Yii::import('ext.runactions.components.ERunActions'); //Extension to flush the cache when needed

        $dependency = new FlushableDependency($id, 'Adspace'); //Dependency for the ad space
        $adspace = Adspace::model()->cache(1800, $dependency)->findByPk($id); //30min of cache duration

        if ($adspace == null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $dependency = new FlushableDependency($id, 'Adspace_Campaignruns'); //Dependency for the ad space's ads
        $campaignrun = Campaignrun::model()->with('campaign')->cache(300, $dependency)->findAllByAttributes(array('adspace_id' => $id, 'status' => 'running')); //5mn of cache duration

        if (!isset($campaignrun[0])) {
            //If there are no ad campaigns on this space
            if (empty($adspace->fallback_html))
                throw new CHttpException(404, 'The requested page does not exist.');
            echo 'document.write("'.$this->jsEscape($adspace->fallback_html).'");';
            return;
        }
        $campaignrun = $campaignrun[rand(0, count($campaignrun) - 1)];

        header('Content-Type: text/html; charset=utf-8'); //We force the charset as utf-8 to avoid any encoding errors
        $adtype = AdTypes::getTypeClass($adspace->type)->loadParameters($campaignrun->campaign->parameters); //We get the ad type
        $adtype->getConfig()->loadConfig($adspace->type_config); //We load the ad space's config
        $ad_code = $adtype->getAdCode($campaignrun->id);
        $ad_code .= '<img style="display:none" src="' . $this->createAbsoluteUrl('stats/process') . '"/>'; //We add a call to process the stats
        echo 'document.write("' . $this->jsEscape($ad_code) . '");'; //And we display the ad
        //ERunActions::runAction('adspace/trackview', array('campaignrun_id' => $campaignrun->id, 'binary_ip' => IPHelper::GetRealRemoteIp()));
        $this->actionTrackView($campaignrun->id, IPHelper::GetRealRemoteIp());
    }

    /**
     * Manages a click on an ad space
     * @param type $id The campaignrun that was clicked's id
     */
    public function actionClick($id) {
        Yii::import('ext.flushable.FlushableDependency'); //Extension to flush the cache when needed
        Yii::import('ext.runactions.components.ERunActions'); //Extension to flush the cache when needed

        $dependency = new FlushableDependency($id, 'Campaignrun'); //Dependency for this campaignrun
        $campaignrun = Campaignrun::model()->with('campaign')->cache(1800, $dependency)->findByPk($id); //30min of cache duration

        $dependency = new FlushableDependency($campaignrun->adspace_id, 'Adspace'); //Dpeendency for the ad space
        $adspace = Adspace::model()->cache(1800, $dependency)->findByPk($campaignrun->adspace_id); //30min of cache duration

        //ERunActions::runAction('adspace/trackclick', array('campaignrun_id' => $campaignrun->id, 'binary_ip' => IPHelper::GetRealRemoteIp()));
        $this->actionTrackClick($campaignrun->id, IPHelper::GetRealRemoteIp()); //Click tracking
        
        $adtype = AdTypes::getTypeClass($adspace->type)->loadParameters($campaignrun->campaign->parameters); //We get the ad type
        $adtype->getConfig()->loadConfig($adspace->type_config); //We load the ad space's config
        $this->redirect($adtype->getTargetUrl()); //We redirect to the target URL
    }

    public function actionTrackView($campaignrun_id, $binary_ip) {
        try {
            //We use an optimized function to insert the view into the database
            $sql = 'INSERT DELAYED INTO `temp_view`(`campaignrun_id`, `time`, `binary_ip`) VALUES(:campaignrunid, :time, :binaryip)';
            Yii::app()->db->createCommand($sql)->bindValues(array(':campaignrunid' => $campaignrun_id, ':time' => date('Y-m-d H:i:s'), ':binaryip' => $binary_ip))->execute();
        } catch (Exception $ex) {
            //We ignore the exception which may appear if we do the query at the wrong time
            //(temp_view currently beign truncated).
        }

        /* return;
          //if (ERunActions::runBackground()) {
          //exit();
          //Run in background in order to add the click to the database
          $temp_view = new TempView();
          $temp_view->campaignrun_id = $campaignrun_id;
          $temp_view->time = date('Y-m-d H:i:s');
          $temp_view->binary_ip = $binary_ip;
          $temp_view->save(false);
          //} */
    }

    public function actionTrackClick($campaignrun_id, $binary_ip) {
        try {
            //We use an optimized function to insert the click into the database
            $sql = 'INSERT DELAYED INTO `temp_click`(`campaignrun_id`, `time`, `binary_ip`) VALUES(:campaignrunid, :time, :binaryip)';
            Yii::app()->db->createCommand($sql)->bindValues(array(':campaignrunid' => $campaignrun_id, ':time' => date('Y-m-d H:i:s'), ':binaryip' => $binary_ip))->execute();
        } catch (Exception $ex) {
            //We ignore the exception which may appear if we do the query at the wrong time
            //(temp_view currently beign truncated).
        }

        /* if (ERunActions::runBackground()) {
          //Run in background in order to add the click to the database
          $temp_click = new TempClick();
          $temp_click->campaignrun_id = $campaignrun_id;
          $temp_click->time = date('Y-m-d H:i:s');
          $temp_click->binary_ip = $binary_ip;
          $temp_click->save();
          } */
    }

    public function actionConfigureAdType($id, $website_id) {
        $model = $this->loadModel($id);

        //We load the adtype
        $adtype = AdTypes::getTypeClass($model->type);
        $adtypeconfig = $adtype->getConfig();

        if (!empty($_POST)) {
            //We set the ad type attributes
            $adtypeconfig->attributes = $_POST[get_class($adtypeconfig)];

            //If we want to save...
            if ($adtypeconfig->validate()) {
                //We want to save
                $model->type_config = $adtypeconfig->encodeConfig(); //We serialize the ad type parameters and add them to the campaign
                if ($model->save()) //Validates and saves the campaign
                    Yii::import('ext.flushable.FlushableDependency'); //Extension to flush the cache when needed
                    FlushableDependency::flushItem($id, 'Adspace'); //We flush the cached data to force a reload!
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('configureadtype', array(
            'model' => $model,
            'adtype' => $adtype,
            'adtypeconfig' => $adtypeconfig,
                //'website' => Website::model()->findByPk($model->website_id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Adspace::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'adspace-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Espace a string like Javascript
     * @param type $str
     * @return type
     */
    private function jsEscape($str) {
        return addcslashes($str, "\\\'\"&\n\r<>");
    }

}
