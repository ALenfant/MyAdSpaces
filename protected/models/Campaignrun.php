<?php

/**
 * This is the model class for table "campaignrun".
 *
 * The followings are the available columns in table 'campaignrun':
 * @property integer $id
 * @property integer $campaign_id
 * @property integer $adspace_id
 * @property string $paytype
 * @property integer $paytype_amount
 * @property string $time_created
 * @property string $time_enabled
 * @property string $time_disabled
 * @property integer $stats_views
 * @property integer $stats_clicks
 * @property string $status
 */
class Campaignrun extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Campaignrun the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'campaignrun';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('campaign_id, adspace_id, paytype, paytype_amount, time_created, time_enabled, time_disabled, status', 'required'),
            array('campaign_id, adspace_id, paytype_amount, stats_views, stats_clicks', 'numerical', 'integerOnly' => true),
            array('paytype', 'length', 'max' => 8),
            array('status', 'length', 'max' => 7),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, campaign_id, adspace_id, paytype, paytype_amount, time_created, time_enabled, time_disabled, stats_views, stats_clicks, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaign_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'campaign_id' => 'Campaign',
            'adspace_id' => 'Adspace',
            'paytype' => 'Paytype',
            'paytype_amount' => 'Paytype Amount',
            'time_created' => 'Time Created',
            'time_enabled' => 'Time Enabled',
            'time_disabled' => 'Time Disabled',
            'stats_views' => 'Stats Views',
            'stats_clicks' => 'Stats Clicks',
            'status' => 'Status',
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
        $criteria->compare('campaign_id', $this->campaign_id);
        $criteria->compare('adspace_id', $this->adspace_id);
        $criteria->compare('paytype', $this->paytype, true);
        $criteria->compare('paytype_amount', $this->paytype_amount);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_enabled', $this->time_enabled, true);
        $criteria->compare('time_disabled', $this->time_disabled, true);
        $criteria->compare('stats_views', $this->stats_views);
        $criteria->compare('stats_clicks', $this->stats_clicks);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Used with a campaign/details CGridView
     * @return \CActiveDataProvider
     */
    public function detailsList($campaign_id) {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('campaign_id', $campaign_id);
        $criteria->compare('adspace_id', $this->adspace_id);
        $criteria->compare('paytype', $this->paytype, true);
        $criteria->compare('paytype_amount', $this->paytype_amount);
        $criteria->compare('time_created', $this->time_created, true);
        $criteria->compare('time_enabled', $this->time_enabled, true);
        $criteria->compare('time_disabled', $this->time_disabled, true);
        $criteria->compare('stats_views', $this->stats_views);
        $criteria->compare('stats_clicks', $this->stats_clicks);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => 'time_created DESC'
                    )
                ));
    }

}