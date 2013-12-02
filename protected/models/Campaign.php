<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $type
 * @property string $status
 * @property string $parameters
 * @property string $time_created
 * @property string $time_updated
 */
class Campaign extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Campaign the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'campaign';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, user_id, type, status, parameters, time_created, time_updated', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('status', 'length', 'max' => 8),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, name, user_id, status, parameters, time_created, time_updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'user_id' => 'User',
            'type' => 'Type',
            'status' => 'Status',
            'parameters' => 'Parameters',
            'time_created' => 'Time created',
            'time_updated' => 'Time updated',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function detailsList($user_id) {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user_id', $user_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_updated', $this->time_updated, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'time_updated DESC'
                    )
                ));
    }

}