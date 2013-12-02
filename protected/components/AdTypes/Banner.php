<?php

/**
 * Displays a banner (currently an image)
 */
class Banner extends AdType {
    /* AdType parameters */

    protected $adtype_uploads = Array('image');
    protected $adtype_config_class_name = 'BannerConfig';

    /* Ad campaign parameters */
    public $image; //Image URL
    public $description; //Description (hover text)

    function Banner() {
        $this->AdType(); //Parent constructor (required)
        Yii::import('application.components.EPhotoValidator.php');
        $this->adtype_display_name = Yii::t('adtype', 'Banner'); //We set the display name
    }

    public function getAdCode($campaignrun_id = null) {
        //Set default description if empty...
        if (empty($this->description)) {
            $description = Yii::t('adtype', 'Click to access {url}...', array('{url}' => $this->link_url));
        } else {
            $description = $this->description;
        }

        //Allow testing before saving to DB
        if ($campaignrun_id != null) {
            $link = $this->getClickUrl($campaignrun_id);
        } else {
            $link = $this->link_url;
        }

        //Create full image URL (relative to allow faster loading)
        $image = Yii::app()->getBaseUrl() . '/' . $this->image;

        return '<a href="' . $link . '"><img src="' . $image . '" title="' . htmlspecialchars($description) . '" alt="Ad banner" style="border-style: none" /></a>';
    }

    public function displayEditForm($form) {
        $model = $this;
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'image'); ?>
            <?php echo $form->fileField($model, 'image', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo '<div style="font-size:10px">'; ?>
            <?php echo Yii::t('adtype', 'The image should be in jpeg (jpg), png or gif format with a dimension of :width*:heightpx and have a maximum filesize of :filesizekB', array(':width' => $this->adtype_config->size_width, ':height' => $this->adtype_config->size_height, ':filesize' => $this->adtype_config->size_file)); ?>
            <?php echo '</div>'; ?>
        <?php echo $form->error($model, 'image'); ?>
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
        <?php
    }

    public function attributeLabels() {
        return array(
            'description' => Yii::t('adtype', 'Description (Hover text)'),
            'link_url' => Yii::t('adtype', 'Link URL'),
        );
    }

    public function rules() {
        return array(
            array('link_url', 'required'),
            array('link_url', 'url'),
            array('description', 'length', 'max' => 400),
            array('image', 'EPhotoValidator',
                'maxSize' => $this->adtype_config->size_file * 1024,
                'mimeType' => array('image/jpeg', 'image/gif', 'image/png'),
                'maxWidth' => $this->adtype_config->size_width, 'maxHeight' => $this->adtype_config->size_height,
                'minWidth' => $this->adtype_config->size_width, 'minHeight' => $this->adtype_config->size_height,
                'except' => 'campaignrun_validation'), //Shouldn't be executed during campaignrun creation, only when creating a new campaign
            array('image', 'EPhotoValidatorFile',
                //'maxSize' => $this->adtype_config->size_file * 1024, //not supported
                'mimeType' => array('image/jpeg', 'image/gif', 'image/png'),
                'maxWidth' => $this->adtype_config->size_width, 'maxHeight' => $this->adtype_config->size_height,
                'minWidth' => $this->adtype_config->size_width, 'minHeight' => $this->adtype_config->size_height,
                'on' => 'campaignrun_validation'), //Shouldn't be executed during campaignrun creation, only when creating a new campaign
        );
    }

}

class BannerConfig extends AdTypeConfig {

    public $size_width = 468; //Width in pixels
    public $size_height = 60; //Height in pixels
    public $size_file = 40; //Maximum filesize in kB

    public function displayEditForm($form) {
        $model = $this;
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'size_width'); ?>
            <?php echo $form->textField($model, 'size_width'); ?>
        <?php echo $form->error($model, 'size_width'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'size_height'); ?>
            <?php echo $form->textField($model, 'size_height'); ?>
        <?php echo $form->error($model, 'size_height'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'size_file'); ?>
            <?php echo $form->textField($model, 'size_file'); ?>
        <?php echo $form->error($model, 'size_file'); ?>
        </div>
        <?php
    }

    public function attributeLabels() {
        return array(
            'size_width' => Yii::t('adtype', 'Width of the banner\'s image in pixels'),
            'size_height' => Yii::t('adtype', 'Height of the banner\'s image in pixels'),
            'size_file' => Yii::t('adtype', 'Filesize of the banner\'s image in kB'),
        );
    }

    public function rules() {
        return array(
            array('size_width, size_height, size_file', 'required'),
            array('size_width, size_height, size_file', 'numerical', 'integerOnly' => true, 'allowEmpty' => false),
        );
    }

}
?>
