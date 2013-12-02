<?php

class CampaignrunController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('details'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Checks the owner of a particular ad campaign
     * @param type $model The model representing the ad campaign to check
     */
    private function checkOwner($model) {
        if ($model->user_id != Yii::app()->user->id) {
            Yii::app()->user->setFlash('error', Yii::t('app', 'The selected ad campaign doesn\'t exist or doesn\'t belongs to you!'));
            $this->redirect(array('member/panel'));
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionDetails($id) {
        $model = $this->loadModel($id);
        $campaign = Campaign::model()->findByPk($model->campaign_id);
        $this->checkOwner($campaign);
        $this->render('details', array(
            'model' => $model,
            'campaign' => $campaign,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Campaignrun::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
