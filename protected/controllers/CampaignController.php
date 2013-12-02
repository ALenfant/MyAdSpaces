<?php

class CampaignController extends Controller {

    public static function validateForAdspace($campaign, $adspace) {
        if ($campaign->type == $adspace->type) {
            //It is the check type, let's check i we can validate the campaign
            $adtype = AdTypes::getTypeClass($adspace->type)->loadParameters($campaign->parameters); //We get the ad type
            $adtype->getConfig()->loadConfig($adspace->type_config); //We load the default config (campaign doesn't depend on an adspace)
            $adtype->setScenario('campaignrun_validation'); //For validation
            if ($adtype->validate($campaign))
                return true;
        }
        return false;
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'run', 'update', 'details', 'manage'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
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
        $campaignrun = Campaignrun::model();

        $this->checkOwner($model);

        $this->render('details', array(
            'model' => $model,
            'campaignrun' => $campaignrun,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate($page = 1, $website_id = -1, $adspace_id = -1) {
        switch ($page) {
            case 1: //Site choice
                $websites = Website::model()->findAll('enabled = 1');
                $this->render('create', array(
                    'page' => $page,
                    'websites' => $websites,
                ));
                break;

            case 2: //Adspace choice
                $website = Website::model()->findByPk($website_id);
                if ($website == null) {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'The selected website doesn\'t exist!'));
                    $this->redirect(array('create'));
                }
                $adspaces = Adspace::model()->findAll('website_id=:websiteid AND enabled=1', array(':websiteid' => $website_id));
                if (count($adspaces) == 0) {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'The selected website doesn\'t has any ad space available!'));
                    $this->redirect(array('create'));
                }
                $this->render('create', array(
                    'page' => $page,
                    'website' => $website,
                    'adspaces' => $adspaces,
                ));
                break;

            case 3: //Potential future page to select the ad type
                break;

            case 4: //Campaign creation
                $model = new Campaign;

                //We get the informations about the adspace and the ad type
                $adspace = Adspace::model()->findByPk($adspace_id);
                $adtype = AdTypes::getTypeClass($adspace->type);
                $adtype->getConfig()->loadConfig($adspace->type_config);

                // Uncomment the following line if AJAX validation is needed
                // $this->performAjaxValidation($model);

                $preview = false;
                if (isset($_POST['Campaign'])) {
                    //We set the campaign attributes
                    $model->attributes = $_POST['Campaign'];
                    $model->user_id = Yii::app()->user->id;
                    $model->type = $adspace->type;
                    $model->time_created = date('Y-m-d', time());
                    $model->time_updated = $model->time_created;
                    $model->status = 'created';
                    $model->parameters = '1'; //Fake adtype parameters to hide the error message
                    //We set the ad type attributes
                    $adtype->attributes = $_POST[get_class($adtype)];

                    //If we want to save...
                    if ($adtype->validate()) {
                        //We check if we have some files to save...
                        foreach ($adtype->getAdtypeUploads() as $upload_parameter) {
                            //For each file uploaded
                            //Get the upload's information, then save it
                            $adtype->$upload_parameter = CUploadedFile::getInstance($adtype, $upload_parameter);

                            //Set the file's name, temporary and final locations
                            $filename = $upload_parameter . '_' . date('Ymd_His') . '_' . uniqid() . '.' . $adtype->$upload_parameter->getExtensionName(); //The file's name
                            $dirpath = 'uploads/preview/'; //Temporary path that will be used for previews
                            $final_dirpath = 'uploads/' . $adspace->website_id . '/' . $adspace_id . '_' . $adspace->type . '/'; //Final path that will be sued if the campaign is saved
                            $upload_finalpath[$upload_parameter] = $final_dirpath . $filename;

                            //Check if the folders exists, create them if not
                            if (!is_dir($dirpath))
                                mkdir($dirpath, 0777, true);
                            if (!is_dir($final_dirpath))
                                mkdir($final_dirpath, 0777, true);

                            $adtype->$upload_parameter->saveAs($dirpath . $filename);
                            $adtype->$upload_parameter = $dirpath . $filename;
                        }

                        if (isset($_POST['preview'])) {
                            //If we want to preview...
                            $preview = true;
                        } else { //We want to save
                            foreach ($adtype->getAdtypeUploads() as $upload_parameter) {
                                //For each uploaded file, we rename then to their final path
                                rename($adtype->$upload_parameter, $upload_finalpath[$upload_parameter]); //Move the files to their final location
                                $adtype->$upload_parameter = $upload_finalpath[$upload_parameter]; //And we set the new value
                            }

                            $model->parameters = $adtype->encodeParameters(); //We serialize the ad type parameters and add them to the campaign
                            if ($model->save()) {
                                foreach ($adtype->getAdtypeUploads() as $upload_parameter) {
                                    //For each uploaded file, we reference it inside Campaignupload
                                    $campaignupload = new Campaignupload();
                                    $campaignupload->campaign_id = $model->id;
                                    $campaignupload->file_path = $adtype->$upload_parameter;
                                    $campaignupload->save();
                                }

                                //Validates and saves the campaign
                                Yii::app()->user->setflash('success', Yii::t('app', 'Your ad campaign was successfully created. You will now be able to run it in order to display it on an ad space.'));
                                $this->redirect(array('run', 'campaign_id' => $model->id));
                            }
                        }
                    } else {
                        //The adtype parameters didn't validate
                        $model->validate(); //To display errors for the adspace too
                    }
                }

                $this->render('create', array(
                    'page' => $page,
                    'model' => $model,
                    'adspace' => $adspace,
                    'adtype' => $adtype,
                    'preview' => $preview,
                ));
                break;

            case 5: //Ad edit page
                break;
        }
    }

    public function actionRun($campaign_id = -1, $campaignrun_id = -1, $page = 1, $adspace_id = -1) {
        $campaign = null;
        $adspace = null;
        $campaignrun = null;
        $invoice_data = null;
        $error = null;

        if ($campaign_id != -1) {
            //If we're in the first part of the wizard 
            $campaign = $this->loadModel($campaign_id);
            $this->checkOwner($campaign);
        } else {
            //No campaign id provided!
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        //Check if the campaign can be used with the chosen adspace
        if ($page >= 2) {
            if ($adspace_id != -1) {
                $adspace = Adspace::model()->findByPk($adspace_id);
                //Check if the campaign can be used in this space!
                if (!CampaignController::validateForAdspace($campaign, $adspace)) {
                    //The campaign cannot be used on the selected adspace
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            } else {
                //No adspace id provided!
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        }

        //Check the paytype and amount if necessary
        if (($page == 2 && !empty($_GET['paytype'])) || $page == 3) {
            try {
                //Check if a paytype is provided and if it's valid
                if (empty($_GET['paytype']) || (!$adspace['payby' . $_GET['paytype'] . '_enabled'])) {
                    throw new Exception(Yii::t('app', 'Please choose a valid pricing model'));
                }

                $paytype = $_GET['paytype'];
                //Check if an amount is provided and if it's valid
                if (!isset($_GET['paytype_' . $paytype . '_amount']) || !is_numeric($_GET['paytype_' . $paytype . '_amount'])) {
                    throw new Exception(Yii::t('app', 'Please enter a valid amount to order'));
                }

                //Check if the amount is correct
                $paytype_amount = $_GET['paytype_' . $paytype . '_amount'];
                if ($paytype_amount < max(array(1, $adspace['payby' . $_GET['paytype'] . '_minimum']))) {
                    throw new Exception(Yii::t('app', 'Please enter an amount to order both positive and superior than the minimum amount'));
                }
            } catch (Exception $ex) {
                if ($page != 2) {
                    //We redirect with the same parameters to the page 2 if necessary
                    $_GET['page'] = 2;
                    $this->redirect($this->createUrl('', $_GET));
                } else {
                    $error = $ex->getMessage();
                }
            }
        }

        switch ($page) {
            case 1: //Choice of website/ad space
                break;

            case 2: //Choice of paytype
                //Everything is OK, let's create the campaignrun...
                /* $campaignrun = new Campaignrun;
                  $campaignrun->campaign_id = $campaign_id;
                  $campaignrun->adspace_id = $adspace_id;
                  $campaignrun->paytype = $paytype;
                  $campaignrun->paytype_amount = $paytype_amount;
                  $campaignrun->time_created = date('Y-m-d H:i:s');
                  $campaignrun->time_enabled = '0000-00-00';
                  $campaignrun->time_disabled = '0000-00-00';
                  $campaignrun->stats_views = 0;
                  $campaignrun->stats_clicks = 0;
                  $campaignrun->status = 'created';

                  if ($campaignrun->save())
                  $this->redirect(array('run', 'page' => 3, 'campaignrun_id' => $campaignrun->id)); */

                if (!empty($_GET['paytype']) && empty($error)) {
                    //Everything's OK, let's proceed to the next page...
                    $this->redirect(array('run', 'page' => 3,
                        'campaign_id' => $campaign_id,
                        'adspace_id' => $adspace_id,
                        'paytype' => $paytype,
                        'paytype_' . $paytype . '_amount' => $paytype_amount
                    ));
                }
                break;

            case 3: //Invoice
                $user = User::model()->findByPk(Yii::app()->user->id);

                //The campaignrun that will potentially be created
                $campaignrun = new Campaignrun;
                $campaignrun->campaign_id = $campaign_id;
                $campaignrun->adspace_id = $adspace_id;
                $campaignrun->paytype = $paytype;
                $campaignrun->paytype_amount = $paytype_amount;
                $campaignrun->time_created = date('Y-m-d H:i:s');
                $campaignrun->time_enabled = '0000-00-00';
                $campaignrun->time_disabled = '0000-00-00';
                $campaignrun->stats_views = 0;
                $campaignrun->stats_clicks = 0;
                $campaignrun->status = 'created';

                //We set the base thing the user will pay for
                switch ($campaignrun->paytype) {
                    case 'click':
                        $invoice_data['paytype_name'] = Yii::t('app', 'Clicks');
                        $invoice_data['paytype_price'] = $adspace->paybyclick_price;
                        break;
                    case 'view':
                        $invoice_data['paytype_name'] = Yii::t('app', 'Views');
                        $invoice_data['paytype_price'] = $adspace->paybyview_price / 1000;
                        break;
                    case 'duration':
                        $invoice_data['paytype_name'] = Yii::t('app', 'Duration (days)');
                        $invoice_data['paytype_price'] = $adspace->paybyduration_price;
                        break;
                }
                //We get how much the user wants
                $invoice_data['paytype_amount'] = $campaignrun->paytype_amount;

                //We calculate the base (or full) price from the two previous elements
                $price = $invoice_data['paytype_amount'] * $invoice_data['paytype_price'];
                $invoice_data['price'] = number_format($price, 2); //Formatting of the price for the invoice

                $invoice_data['final_price'] = $invoice_data['price']; //We set the full price as current final price
                $invoice_data['account_balance'] = $user->balance; //We get the current user's balance
                //If a coupon was used
                if (!empty($_GET['coupon'])) {
                    $coupon = $_GET['coupon'];
                    //We lookup the coupon...
                    $coupon = Coupon::model()->find('code = :code', array(':code' => $coupon));

                    //Check if it exists...
                    if ($coupon == null) {
                        Yii::app()->user->setflash('error', Yii::t('app', 'The coupon you entered is invalid.'));
                        break;
                    }

                    //If it's during the validity period
                    if (!(strtotime($coupon->start_date) <= time() && strtotime($coupon->end_date) >= time())) {
                        Yii::app()->user->setflash('error', Yii::t('app', 'The coupon you entered is expired or not yet available.'));
                        break;
                    }

                    //If it has already been used too many times
                    if (!empty($coupon->uses_per_account)) {
                        //We only check if there's a limit
                        $timesused = Spending::model()->count('coupon_id = :couponid AND user_id = :userid', array(':couponid' => $coupon->id, ':userid' => Yii::app()->user->id));
                        if ($timesused >= $coupon->uses_per_account) {
                            Yii::app()->user->setflash('error', Yii::t('app', 'The coupon you entered has reached the maximum number of uses possible on your account.'));
                            break;
                        }
                    }

                    //Everything seems OK, let's apply the coupon
                    switch ($coupon->type) {
                        case 'percent': //Percentage reduction
                            $invoice_data['final_price'] *= (100 - $coupon->amount) / 100;
                            break;

                        case 'absolute': //Absolute reduction
                            $invoice_data['final_price'] -= $coupon->amount;
                            break;
                    }

                    //Display a message confirming the use of the coupon
                    Yii::app()->user->setflash('success', 'The coupon you entered has been successfully applied');

                    //Change the final price if negative
                    $invoice_data['final_price'] = max(array($invoice_data['final_price'], 0));
                    //Format it for display
                    $invoice_data['final_price'] = number_format($invoice_data['final_price'], 2);

                    //We store the coupon savings
                    $invoice_data['coupon_savings'] = $invoice_data['price'] - $invoice_data['final_price']; //We calculate the total reduction thanks to the coupon
                }

                //Calculation of the amount of money necessary not in the user's account
                $invoice_data['needed_fund_amount'] = number_format($invoice_data['final_price'] - $invoice_data['account_balance'], 2);
                $invoice_data['needed_fund_amount'] = ($invoice_data['needed_fund_amount'] < 0) ? 0 : $invoice_data['needed_fund_amount']; //If negative then 0
                //If the user wants to run the campaign (button clicked)
                if (!empty($_POST)) {
                    //Basic verifications
                    if ($invoice_data['needed_fund_amount'] > 0) {
                        Yii::app()->user->setflash('error', Yii::t('app', 'You need to fund your account to continue.'));
                        break;
                    }

                    //Everything is okay.
                    //1. Take the money from the user's account
                    $user->balance -= $invoice_data['final_price'];
                    $user->save();

                    //2. We update the Campaignrun
                    $campaignrun->status = 'running';
                    $campaignrun->time_enabled = date('Y-m-d H:i:s');
                    $campaignrun->save();

                    //3. Store the spending
                    $spending = new Spending();
                    $spending->user_id = $user->id;
                    $spending->date = date('Y-m-d H:i:s');
                    $spending->type = 'campaignrun_created'; //Type of spending
                    $spending->element_id = $campaignrun->id; //Element paid for (here the new campaignrun)
                    $spending->full_amount = $invoice_data['price']; //Full base amount
                    $spending->final_amount = $invoice_data['final_price']; //Final amount paid (can be lower than full amount if coupon used)
                    if (isset($coupon))
                        $spending->coupon_id = $coupon->id;
                    $spending->save();

                    //4. Confirmation
                    //By E-mail
                    Yii::import('application.components.SiteEmail');
                    $email = SiteEmail::CreateEmail();
                    $email->view = 'campaignrun_created';
                    $email->setBody(array('user' => $user, 'invoice_data' => $invoice_data, 'campaign' => $campaign, 'campaignrun' => $campaignrun), 'text/html');
                    $email->addTo($user->email);
                    Yii::app()->mail->send($email);
                    //And flash message
                    Yii::app()->user->setFlash('success', Yii::t('app', 'Your campaign run was successfully created and will appear in a few minutes on the website.'));

                    //5. Redirect the user to the last page
                    $this->redirect($this->createUrl('', array('page' => 4, 'campaign_id' => $campaign_id, 'campaignrun_id' => $campaignrun->id)));
                }
                break;
        }

        $this->render('run', array(
            'page' => $page,
            'campaign' => $campaign,
            'adspace' => $adspace,
            'campaignrun' => $campaignrun,
            'campaign_id' => $campaign_id,
            'error' => $error,
            'invoicedata' => $invoice_data,
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

        if (isset($_POST['Campaign'])) {
            $model->attributes = $_POST['Campaign'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Campaign');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Campaign']))
            $model->attributes = $_GET['Campaign'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionManage() {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Campaign']))
            $model->attributes = $_GET['Campaign'];

        $this->render('manage', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Campaign::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadAdSpace($id) {
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'campaign-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
