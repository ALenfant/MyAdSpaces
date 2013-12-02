<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;

    public function authenticate() {
        /* $users=array(
          // username => password
          'demo'=>'demo',
          'admin'=>'admin',
          );
          if(!isset($users[$this->username]))
          $this->errorCode=self::ERROR_USERNAME_INVALID;
          else if($users[$this->username]!==$this->password)
          $this->errorCode=self::ERROR_PASSWORD_INVALID;
          else
          $this->errorCode=self::ERROR_NONE;
          return !$this->errorCode; */
        $user = User::model()->find('username=:username OR email=:username', array(':username' => $this->username));

        if ($user === null) {
            //Wrong username
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!$user->validatePassword($this->password)) {
            //Wrong password
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            //Login successful
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->setState('role', $user->role);
            $this->errorCode = self::ERROR_NONE;

            //We log the login in the database...
            $login = new Login();
            $login->user_id = $user->id;
            $login->login_date = date('Y-m-d H:i:s');
            $login->login_ip = $_SERVER['REMOTE_ADDR'];
            $login->save();
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}