<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        //$this->render('index');

        if (Yii::app()->user->isGuest) {
            //Redirection to login
            $this->redirect(Yii::app()->createUrl('site/login'));
        } elseif (Yii::app()->user->role === 'admin') {
            //Redirection to admin panel
            $this->redirect(Yii::app()->createUrl('admin/panel'));
        } else {
            //Redirection to member panel
            $this->redirect(Yii::app()->createUrl('member/panel'));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('success', Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.'));
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                //Login OK, let's store it in the database
                if (Yii::app()->user->role === 'admin') {
                    //Administrator, we redirect him to the admin panel
                    $this->redirect(Yii::app()->createUrl('admin/panel'));
                } else {
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Displays the register page 
     */
    public function actionRegister() {
        $model = new User('register');

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->role = 'user';
            $model->register_date = date('Y-m-d H:i:s');
            $model->register_ip = $_SERVER['REMOTE_ADDR'];
            
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'Your account was created successfully, you can now login.'));
                $this->redirect(array('site/login'));
            }
        }

        $this->render('register', array('model' => $model));
    }

    /**
     * Allows the recovery of a lost password
     */
    public function actionLostPassword($page = 1, $user_id = -1, $hash = '') {
        switch ($page) {
            case 1:
                if (!empty($_POST['email'])) {
                    $user = User::model()->findByAttributes(array('email' => $_POST['email']));
                    if ($user != null) {
                        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
                        $user->password_hash = md5(rand() . $user->username . $user->password);
                        $user->password_new = $password;
                        $user->save();

                        $link = $this->createAbsoluteUrl('applysettings', array('user_id' => $user->id, 'type' => 'password', 'hash' => $user->password_hash));

                        $email = SiteEmail::CreateEmail();
                        $email->addTo($user->email);
                        $email->setSubject(Yii::t('app', 'Lost password on {sitename}', array('{sitename}' => Yii::app()->name)));
                        $email->view = 'lostpassword';
                        $email->setBody(array('link' => $link, 'password' => $password, 'username' => $user->username), 'text/html');
                        Yii::app()->mail->send($email);

                        $this->redirect($this->createUrl('', array('page' => 2)));
                    }
                }
                break;

            /* case 3:
              $user = User::model()->findByAttributes(array('id' => $user_id, 'password_hash' => $hash));
              if ($user != null) {
              $user->salt = ''; //To force the regen of the password and salt
              $user->password = $user->password_new;
              $user->password_hash = '';
              $user->save();
              } else {
              throw new CHttpException(404, 'The requested page does not exist.');
              }
              break; */
        }

        $this->render('lostpassword', array(
            'page' => $page
        ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Ajax validation
     * @param type $model 
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}