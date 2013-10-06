<?
/**
 * a radio option element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class RadioOption extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'checked' => null,
			'type' => 'radio'
		);
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return RadioOption
	 */
	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		HTML::renderRadioOption($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}