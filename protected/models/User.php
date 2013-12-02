<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $role
 * @property string $balance
 * @property string $fund_amount
 * @property string $fund_amount_funded
 * @property string $password_hash
 * @property string $password_new
 * @property string $email_hash
 * @property string $email_new
 * @property string $register_date
 * @property string $register_ip
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, role', 'required'),
                        array('username, email', 'unique'),
			array('username, email', 'length', 'max'=>255),
			array('register_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, salt, email, role, balance, fund_amount, fund_amount_funded, password_hash, password_new, email_hash, email_new, register_date, register_ip', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app', 'ID'),
			'username' => Yii::t('app', 'Username'),
			'password' => Yii::t('app', 'Password'),
			'salt' => Yii::t('app', 'Salt'),
			'email' => Yii::t('app', 'E-mail'),
			'role' => Yii::t('app', 'Role'),
			'balance' => Yii::t('app', 'Balance'),
			'fund_amount' => Yii::t('app', 'Fund Amount'),
			'fund_amount_funded' => Yii::t('app', 'Fund Amount Funded'),
			'password_hash' => Yii::t('app', 'Password Hash'),
			'password_new' => Yii::t('app', 'Password New'),
			'email_hash' => Yii::t('app', 'Email Hash'),
			'email_new' => Yii::t('app', 'Email New'),
			'register_date' => Yii::t('app', 'Register Date'),
			'register_ip' => Yii::t('app', 'Register Ip'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('balance',$this->balance,true);
		$criteria->compare('fund_amount',$this->fund_amount,true);
		$criteria->compare('fund_amount_funded',$this->fund_amount_funded,true);
		$criteria->compare('password_hash',$this->password_hash,true);
		$criteria->compare('password_new',$this->password_new,true);
		$criteria->compare('email_hash',$this->email_hash,true);
		$criteria->compare('email_new',$this->email_new,true);
		$criteria->compare('register_date',$this->register_date,true);
		$criteria->compare('register_ip',$this->register_ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /*
        * Executed before saving
        */

       public function beforeSave() {
           if (!empty($this->password) && empty($this->salt)) {
               $globalsalt = Yii::app()->params['globalSalt']; //Load the salt from the configuration
               $this->salt = $this->generateUserSalt();
               $this->password = $this->hashPassword($this->password, $this->salt, $globalsalt);
           }
           return parent::beforeSave();
       }

       /**
        * Validate a password
        * @param type $password 
        */
       public function validatePassword($password) {
           /* var_dump(array($password, $this->salt, $this->globalsalt));
             var_dump($this->hashPassword($password, $this->salt, $this->globalsalt));
             var_dump($this->password);
             exit(); */
           $globalsalt = Yii::app()->params['globalSalt']; //Load the salt from the configuration
           return ($this->hashPassword($password, $this->salt, $globalsalt) === $this->password);
       }

       /**
        * Hash a password
        * @param type $password
        * @param type $usersalt
        * @param type $globalsalt
        * @return type 
        */
       private function hashPassword($password, $usersalt, $globalsalt) {
           return md5($globalsalt . $usersalt . $password);
       }

       /**
        * Generate a user's salt (when registering) 
        */
       public function generateUserSalt() {
           return $this->randString(15);
       }

       /**
        * Generate a random string
        * @param type $length
        * @param type $charset
        * @return type 
        */
       private function randString($length, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
           $str = '';
           $count = strlen($charset);
           while ($length--) {
               $str .= $charset[mt_rand(0, $count - 1)];
           }
           return $str;
       }
}