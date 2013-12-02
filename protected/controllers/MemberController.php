<?php

class MemberController extends Controller {

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
            array('allow', // allow authenticated user
                'actions' => array('panel', 'fund', 'settings'),
                'users' => array('@'),
            ),
            array('allow', // allow everybody
                'actions' => array('applysettings'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays the member panel
     */
    public function actionPanel() {
        $this->render('panel');
    }

    /**
     * Funds an account
     * @param type $page
     * @param type $sub_page
     * @param type $return_url
     * @throws CHttpException
     */
    public function actionFund($page = 1, $sub_page = 1, $return_url = '') {
        $error = null;
        $provider = null;
        $display_amount_remaining = null;

        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($page == 1) {
            $amount = !empty($_GET['amount']) ? $_GET['amount'] : 100;
        } else {
            $amount = $user->fund_amount * 100;
        }
        $display_amount = number_format($amount / 100, 2);

        switch ($page) {
            case 1: //Choose the amount
                if (!empty($_POST)) {
                    //We convert the amount to integer
                    $amount_posted = $_POST['amount'];
                    $display_amount = $amount_posted;
                    $decimal_numbers = strpos($amount_posted, '.');
                    $decimal_numbers = ($decimal_numbers === false) ? 0 : strlen($amount_posted) - $decimal_numbers - 1;
                    $amount_posted = str_replace('.', '', $amount_posted);
                    //Check if the number is a valid amount
                    if (!is_numeric($amount_posted) || $decimal_numbers > 2) {
                        $error = Yii::t('app', 'Please enter a valid amount to add to your account');
                        break;
                    }
                    $amount = ((int) $amount_posted) * pow(10, 2 - $decimal_numbers);

                    //Check if we'll have an overflow
                    if (strlen($amount) > 6) {
                        $error = Yii::t('app', 'Please enter a lower amount than 10000');
                        break;
                    }

                    //We save the amount in the user's account...
                    $user->fund_amount = $amount / 100;
                    $user->fund_amount_funded = 0;
                    $user->save();

                    //We redirect him
                    $this->redirect($this->createUrl('', array('page' => 2, 'return_url' => $return_url)));
                }
                break;

            case 3: //Provider payment management page(s)
                if (empty($_GET['provider'])) {
                    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
                }
                $provider = $_GET['provider'];
                $provider = PaymentProviders::getProviderClass($provider);

                $provider->processPayment($sub_page); //Do payment processing work

                $user->refresh();

                $display_amount_remaining = $user->fund_amount - $user->fund_amount_funded;

                if ($display_amount_remaining <= 0) {
                    //Everything needed is funded!
                    $user->fund_amount = 0;
                    $user->fund_amount_funded = 0;
                    $user->save();
                    $this->redirect($this->createUrl('', array('page' => 4, 'return_url' => $return_url)));
                }
                break;
        }

        $this->render('fund', array(
            'page' => $page,
            'sub_page' => $sub_page,
            'user' => $user,
            'display_amount' => $display_amount,
            'display_amount_remaining' => number_format($display_amount_remaining, 2),
            'provider' => $provider,
            'error' => $error,
            'return_url' => $return_url,
        ));
    }

    /**
     * Displays the account settings
     */
    public function actionSettings() {
        if (!empty($_POST))
            $user = User::model()->findByPk(Yii::app()->user->id);

        if (isset($_POST['passwordform'])) {
            if ($user->validatePassword($_POST['password'])) {
                $newpassword = $_POST['newpassword'];
                $validator = new CEmailValidator();
                if (!empty($newpassword)) {
                    if ($newpassword == $_POST['newpassword2']) {
                        $user->password_hash = md5(rand() . $user->username . $user->email);
                        $user->password_new = $newpassword;
                        $user->save();

                        $link = $this->createAbsoluteUrl('applysettings', array('user_id' => $user->id, 'type' => 'password', 'hash' => $user->password_hash));

                        $email = SiteEmail::CreateEmail();
                        $email->addTo($user->email);
                        $email->setSubject(Yii::t('app', 'Password change on {sitename}', array('{sitename}' => Yii::app()->name)));
                        $email->view = 'passwordchange';
                        $email->setBody(array('link' => $link, 'username' => $user->username), 'text/html');
                        Yii::app()->mail->send($email);

                        //$this->redirect($this->createUrl('', array('page' => 2)));
                        Yii::app()->user->setFlash('success', Yii::t('app', 'A confirmation e-mail was sent, please open it and click on the link inside'));
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('app', 'The new passwords you entered are not the same'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'The new password you entered is empty'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('app', 'The current password you entered is wrong'));
            }
        } elseif (isset($_POST['emailform'])) {
            if ($_POST['email'] == $user->email) {
                $newemail = $_POST['newemail'];
                $validator = new CEmailValidator();
                if ($validator->validateValue($newemail)) {
                    if ($newemail == $_POST['newemail2']) {
                        $user->email_hash = md5(rand() . $user->username . $user->email);
                        $user->email_new = $newemail;
                        $user->save();

                        $link = $this->createAbsoluteUrl('applysettings', array('user_id' => $user->id, 'type' => 'email', 'hash' => $user->email_hash));

                        $email = SiteEmail::CreateEmail();
                        $email->addTo($user->email);
                        $email->setSubject(Yii::t('app', 'E-mail change on {sitename}', array('{sitename}' => Yii::app()->name)));
                        $email->view = 'emailchange';
                        $email->setBody(array('link' => $link, 'email' => $newemail, 'username' => $user->username), 'text/html');
                        Yii::app()->mail->send($email);

                        //$this->redirect($this->createUrl('', array('page' => 2)));
                        Yii::app()->user->setFlash('success', Yii::t('app', 'A confirmation e-mail was sent, please open it and click on the link inside'));
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('app', 'The new e-mail addresses you entered are not the same'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('app', 'The new e-mail address you entered is not a valid e-mail address'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('app', 'The current e-mail address you entered is wrong'));
            }
        }
        //var_dump($_POST);
        $this->render('settings');
    }

    /**
     * Applies a change of e-mail or password (from e-mail link)
     * @param type $user_id Account id
     * @param type $type email or password
     * @param type $hash Security hash
     */
    function actionApplySettings($user_id, $type, $hash) {
        $user = User::model()->findByPk($user_id);
        if ($user != null) {
            $checkhash = false;
            switch ($type) {
                case 'email':
                    $checkhash = $user->email_hash;
                    $user->email = $user->email_new;
                    $user->email_hash = '';
                    $user->email_new = '';
                    break;

                case 'password':
                    $checkhash = $user->password_hash;
                    $user->password = $user->password_new;
                    $user->password_hash = '';
                    $user->password_new = '';
                    $user->salt = ''; //To reset the passwords
                    break;
            }

            if ($hash == $checkhash) {
                if ($user->save()) {
                    $this->render('applysettings');
                } else {
                    exit('ErrorS');
                }
            } else {
                exit('ErrorH');
            }
        } else {
            exit('ErrorI');
        }
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}