<?php

/**
 * This is the model class for table "adspace".
 *
 * The followings are the available columns in table 'adspace':
 * @property integer $id
 * @property integer $website_id
 * @property string $name
 * @property string $description
 * @property string $image_url
 * @property string $type
 * @property string $type_config
 * @property string $url
 * @property integer $enabled
 * @property integer $paybyclick_enabled
 * @property string $paybyclick_price
 * @property integer $paybyclick_minimum
 * @property integer $paybyview_enabled
 * @property string $paybyview_price
 * @property integer $paybyview_minimum
 * @property integer $paybyduration_enabled
 * @property string $paybyduration_price
 * @property integer $paybyduration_minimum
 */
class Adspace extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Adspace the static model class
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
		return 'adspace';
	}
        
        public function checkIfPaymentMethodSet($attribute,$params)
        {
            if ($this->$attribute == 1 && ($this->paybyclick_enabled == 0 && $this->paybyview_enabled == 0 && $this->paybyduration_enabled == 0)) {
                $this->addError($attribute, Yii::t('app', 'You need to enable at least one pricing model if you want to enable this ad space'));
            }
        }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('website_id, name, type, enabled, paybyclick_minimum, paybyview_minimum, paybyduration_minimum', 'required'),
			array('website_id, enabled, paybyclick_enabled, paybyclick_minimum, paybyview_enabled, paybyview_minimum, paybyduration_enabled, paybyduration_minimum', 'numerical', 'integerOnly'=>true),
			array('name, image_url, url', 'length', 'max'=>255),
			array('description', 'length', 'max'=>1024),
			array('type', 'length', 'max'=>12),
			array('paybyclick_price, paybyview_price, paybyduration_price', 'length', 'max'=>5), //TODO: Add numerical validation
                        array('enabled', 'checkIfPaymentMethodSet'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, website_id, name, description, image_url, type, url, enabled, paybyclick_enabled, paybyclick_price, paybyclick_minimum, paybyview_enabled, paybyview_price, paybyview_minimum, paybyduration_enabled, paybyduration_price, paybyduration_minimum', 'safe', 'on'=>'search'),
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
			'website_id' => 'Website',
			'name' => 'Name',
			'description' => 'Description',
			'image_url' => 'Image Url',
			'type' => 'Type',
                        'type_config' => 'Type Config',
			'url' => 'Url',
			'enabled' => 'Enabled',
			'paybyclick_enabled' => 'Pay by click',
			'paybyclick_price' => 'Pay by click Price',
			'paybyclick_minimum' => 'Pay by click Minimum',
			'paybyview_enabled' => 'Pay by view',
			'paybyview_price' => 'Pay by view Price',
			'paybyview_minimum' => 'Pay by view Minimum',
			'paybyduration_enabled' => 'Pay by duration',
			'paybyduration_price' => 'Pay by duration Price',
			'paybyduration_minimum' => 'Pay by duration Minimum',
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
		$criteria->compare('website_id',$this->website_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image_url',$this->image_url,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('paybyclick_enabled',$this->paybyclick_enabled);
		$criteria->compare('paybyclick_price',$this->paybyclick_price,true);
		$criteria->compare('paybyclick_minimum',$this->paybyclick_minimum);
		$criteria->compare('paybyview_enabled',$this->paybyview_enabled);
		$criteria->compare('paybyview_price',$this->paybyview_price,true);
		$criteria->compare('paybyview_minimum',$this->paybyview_minimum);
		$criteria->compare('paybyduration_enabled',$this->paybyduration_enabled);
		$criteria->compare('paybyduration_price',$this->paybyduration_price,true);
		$criteria->compare('paybyduration_minimum',$this->paybyduration_minimum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}