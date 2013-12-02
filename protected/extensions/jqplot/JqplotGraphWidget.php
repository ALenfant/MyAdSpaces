<?php
Yii::import('application.extensions.jqplot.JqplotWidget');
class JqplotGraphWidget extends JqplotWidget{
	/**
	 * @var string the name of the container element that contains the progress bar. Defaults to 'div'.
	 */
	public $tagName = 'div';

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run(){
		$id=$this->getId();
		$this->htmlOptions['id']=$id;        

		echo CHtml::openTag($this->tagName,$this->htmlOptions);
		echo CHtml::closeTag($this->tagName);

		$plotdata=CJavaScript::encode($this->data);
		$flotoptions=CJavaScript::encode($this->options);
		
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"$.jqplot('$id', $plotdata, $flotoptions);");
	}

}