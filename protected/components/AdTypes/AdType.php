<?php

/*
 * Describes an abstract Ad Type
 * Must be extended by ad types
 * 
 * Extends CFormModel in order to provide native validation
 */

abstract class AdType extends CFormModel {

    //Protected parameters that will not be exported when saving a campaign's parameters
    protected $adtype_display_name = 'Ad Type [TO NAME! See AdTypes!]'; //Name displayed
    protected $adtype_uploads = Array(); //List of the files uploaded when creating a campaign with this type
    protected $adtype_config_class_name = 'AdTypeConfig'; //Name of this AdType's config class
    protected $adtype_config = null; //Instance of adtype config
    //Parameters to store for each campaign (public)
    public $link_url = '';

    abstract function displayEditForm($form);

    abstract function getAdCode($campaignrun_id = null);

    function AdType() {
        $adtype_config = $this->getConfig();
    }

    function getDisplayName() {
        return $this->adtype_display_name;
    }

    function getTargetUrl() {
        return $this->link_url;
    }

    function getClickUrl($campaignrun_id) {
        return Yii::app()->createAbsoluteUrl('adspace/click', array('id' => $campaignrun_id));
    }

    //Get the AdTypeConfig class
    function getConfig() {
        if ($this->adtype_config == null)
            $this->adtype_config = new $this->adtype_config_class_name();
        return $this->adtype_config;
    }

    //Get the adtypes uploads that reference the files that should be uploaded
    function getAdtypeUploads() {
        return $this->adtype_uploads;
    }

    function displayAd($campaignrun_id = null) {
        echo $this->getAdCode();
    }

    function loadParameters($parameters) {
        $parameters = $this->decodeParameters($parameters);
        foreach ($parameters as $parameter => $value) {
            $this->$parameter = $value;
        }

        return $this;
    }

    /* function loadTypeParameters($type_parameters) {
      $this->type_parameters = $this->decodeParameters($type_parameters);
      return $this;
      } */

    function encodeParameters() {
        return json_encode($this->attributes);
    }

    function decodeParameters($parameters) {
        return json_decode($parameters, true);
    }

}

/**
 * Represents an ad type's configuration
 * Not abstract since some AdTypes have no configuration
 * Can be extended if an ad type needs additional properties
 */
class AdTypeConfig extends CFormModel {

    function displayEditForm($form) {
        echo '<em>' . Yii::t('adtype', 'There is nothing to configure for this ad type.') . '</em>';
    }

    function loadConfig($config) {
        if (empty($config))
            $config = '{}'; //Empty JSON
        $config = $this->decodeConfig($config);
        foreach ($config as $parameter => $value) {
            $this->$parameter = $value;
        }

        return $this;
    }

    function encodeConfig() {
        return json_encode($this->attributes);
    }

    function decodeConfig($config) {
        return json_decode($config, true);
    }

}

?>
