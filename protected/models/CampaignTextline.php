<?php

/**
 * This is the model class for table "campaign".
 *
 * The followings are the available columns in table 'campaign':
 * @property string $id
 * @property string $name
 * @property string $user_id
 * @property integer $time_created
 * @property integer $time_disabled
 * @property string $title
 * @property string $description
 * @property string $link_url
 * @property string $link_title
 * @property integer $textstyle_bold
 * @property integer $textstyle_underline
 * @property integer $textstyle_italic
 * @property integer $color_text
 * @property integer $color_background
 * @property integer $color_border
 * @property integer $stats_views
 * @property integer $stats_clicks
 * @property string $status
 *
 * The followings are the available model relations:
 * @property User $user
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
            array('name, user_id, time_created, title, description, link_url, link_title, textstyle_bold, textstyle_underline, textstyle_italic, color_text, stats_views, stats_clicks, status', 'required'),
            array('time_created, time_disabled, textstyle_bold, textstyle_underline, textstyle_italic, color_text, color_background, color_border, stats_views, stats_clicks', 'numerical', 'integerOnly' => true),
            array('name, description, link_url, link_title', 'length', 'max' => 255),
            array('user_id', 'length', 'max' => 11),
            array('title', 'length', 'max' => 100),
            array('status', 'length', 'max' => 7),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, user_id, time_created, time_disabled, title, description, link_url, link_title, textstyle_bold, textstyle_underline, textstyle_italic, color_text, color_background, color_border, stats_views, stats_clicks, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Campaign name'),
            'user_id' => Yii::t('app', 'User'),
            'time_created' => Yii::t('app', 'Created on'),
            'time_disabled' => Yii::t('app', 'Disabled on'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'link_url' => Yii::t('app', 'Link Url'),
            'link_title' => Yii::t('app', 'Link Title'),
            'textstyle_bold' => Yii::t('app', 'Bold'),
            'textstyle_underline' => Yii::t('app', 'Underline'),
            'textstyle_italic' => Yii::t('app', 'Italic'),
            'color_text' => Yii::t('app', 'Text Color'),
            'color_background' => Yii::t('app', 'Background Color'),
            'color_border' => Yii::t('app', 'Border Color'),
            'stats_views' => Yii::t('app', 'Views'),
            'stats_clicks' => Yii::t('app', 'Clicks'),
            'status' => Yii::t('app', 'Status'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('time_created', $this->time_created);
        $criteria->compare('time_disabled', $this->time_disabled);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('link_url', $this->link_url, true);
        $criteria->compare('link_title', $this->link_title, true);
        $criteria->compare('textstyle_bold', $this->textstyle_bold);
        $criteria->compare('textstyle_underline', $this->textstyle_underline);
        $criteria->compare('textstyle_italic', $this->textstyle_italic);
        $criteria->compare('textstyle_marquee', $this->textstyle_marquee);
        $criteria->compare('stats_views', $this->stats_views);
        $criteria->compare('stats_clicks', $this->stats_clicks);
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}