<?
class RadioOption extends DomElementAbstract{
	public static function getDefaultConf(){
		return array(
			'checked' => null
		);
	}

	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

	public function display(){
		$this->displayLabelBefore();
		HTML::renderRadioOption($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}