<?php

/**
 * This is the model class for table "paymenttransaction".
 *
 * The followings are the available columns in table 'paymenttransaction':
 * @property integer $id
 * @property string $transaction_id
 * @property string $payment_provider
 * @property string $amount_funded
 * @property string $time
 * @property integer $user_id
 * @property string $details
 */
class Paymenttransaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Paymenttransaction the static model class
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
		return 'paymenttransaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transaction_id, payment_provider, amount_funded, time, user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('transaction_id, payment_provider', 'length', 'max'=>255),
			array('amount_funded', 'length', 'max'=>6),
			array('details', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, transaction_id, payment_provider, amount_funded, time, user_id, details', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'transaction_id' => 'Transaction',
			'payment_provider' => 'Payment Provider',
			'amount_funded' => 'Amount Funded',
			'time' => 'Time',
			'user_id' => 'User',
			'details' => 'Details',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('payment_provider',$this->payment_provider,true);
		$criteria->compare('amount_funded',$this->amount_funded,true);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('details',$this->details,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}