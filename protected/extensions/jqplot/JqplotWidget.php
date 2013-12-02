<?php

abstract class JqplotWidget extends CWidget{
	public $scriptUrl;

	public $themeUrl;

	public $theme='base';

	public $scriptFile=array('excanvas.min.js','jquery.jqplot.js');
	
	public $pluginScriptFile=array();
	
	public $cssFile=array('jquery.jqplot.css');

	public $data=array();
	
	public $options=array();

	public $htmlOptions=array();

	public function init(){
		$this->resolvePackagePath();
		$this->registerCoreScripts();
		parent::init();
	}

	protected function resolvePackagePath(){
		if($this->scriptUrl===null || $this->themeUrl===null){
			$basePath=Yii::getPathOfAlias('application.extensions.jqplot.assets');
			$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
			if($this->scriptUrl===null)
				$this->scriptUrl=$baseUrl.'';
			if($this->themeUrl===null)
				$this->themeUrl=$baseUrl.'';
		}
	}

	protected function registerCoreScripts(){
		$cs=Yii::app()->getClientScript();
		if(is_string($this->cssFile))
			$this->registerCssFile($this->cssFile);
		else if(is_array($this->cssFile)){
			foreach($this->cssFile as $cssFile)
				$this->registerCssFile($cssFile);
		}

		$cs->registerCoreScript('jquery');
		if(is_string($this->scriptFile))
			$this->registerScriptFile($this->scriptFile);
		else if(is_array($this->scriptFile)){
			foreach($this->scriptFile as $scriptFile)
				$this->registerScriptFile($scriptFile);
		}
		if(is_string($this->pluginScriptFile))
			$this->registerPluginScriptFile($this->pluginScriptFile);
		else if(is_array($this->pluginScriptFile)){
			foreach($this->pluginScriptFile as $scriptFile)
				$this->registerPluginScriptFile($scriptFile);
		}
	}

	protected function registerScriptFile($fileName,$position=CClientScript::POS_HEAD){
		Yii::app()->clientScript->registerScriptFile($this->scriptUrl.'/'.$fileName,$position);
	}
	protected function registerPluginScriptFile($fileName,$position=CClientScript::POS_HEAD){
		Yii::app()->clientScript->registerScriptFile($this->scriptUrl.'/plugins/'.$fileName,$position);
	}
	protected function registerCssFile($fileName){
		Yii::app()->clientScript->registerCssFile($this->themeUrl.'/'.$fileName);
	}
}
