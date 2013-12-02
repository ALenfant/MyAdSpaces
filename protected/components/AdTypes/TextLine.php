<?php

/**
 * Displays a line of text with the name of the advertised website, a short description and a link to it
 */
class TextLine extends AdType {

    public $title;
    public $description;
    public $link_url;
    public $link_title;

    public function TextLine() {
        $this->AdType(); //Parent constructor (required)
        $this->adtype_display_name = Yii::t('adtype', 'Text Line'); //We set the display name
    }

    public function getAdCode($campaignrun_id = null) {
        if (empty($this->link_title)) {
            $this->link_title = Yii::t('adtype', 'More information...');
        }
        if ($campaignrun_id != null) {
            $link = $this->getClickUrl($campaignrun_id);
        } else {
            $link = $this->link_url;
        }

        return '<table border="0" width="100%" style="border-spacing: 0px; height: 36px; font-family:Tahoma; font-size:14px; color: white; text-shadow: 0px -1px #C48733; "><tr><td align="center" style="background: url(\''.Yii::app()->baseURL.'/images/adtypes/textline/ads_repeat.png\');"><strong>' . htmlspecialchars($this->title) . ' :</strong> ' . htmlspecialchars($this->description) . ' <span id="adsbar"><a style="color: white;" href="' . $link . '" title="Cliquer sur ce lien vous m&egrave;nera vers la page ' . $this->link_url . '" target="_blank">' . htmlspecialchars($this->link_title) . '</a></span></td><td align="right" width="207" style="padding:0px; background: url(\''.Yii::app()->baseURL.'/images/adtypes/textline/ads_right.png\');"><span id="adsbar" style="color: white; font-weight: bold;"><a style="padding-left:30px; color: white;" id="adsbar" href="' . Yii::app()->request->hostInfo . Yii::app()->baseURL . '">' . Yii::t('adtype', 'Your ad here') . '</a></span></td></tr></table>';
    }

    public function displayEditForm($form) {
        $model = $this;
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'link_url'); ?>
            <?php echo $form->textField($model, 'link_url', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'link_url'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'link_title'); ?>
            <?php echo $form->textField($model, 'link_title', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'link_title'); ?>
        </div>
        <?php
    }

    public function attributeLabels() {
        return array(
            'title' => Yii::t('adtype', 'Title'),
            'description' => Yii::t('adtype', 'Description'),
            'link_url' => Yii::t('adtype', 'Link URL'),
            'link_title' => Yii::t('adtype', 'Link Title'),
        );
    }

    public function rules() {
        return array(
            array('title, description, link_url', 'required'),
            array('link_url', 'url'),
            array('title, link_title', 'length', 'max' => 200),
            array('description', 'length', 'max' => 400),
        );
    }

}
?>
