<?
/**
 * a checkbox element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Checkbox extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'checked' => null,
			'value' => null,
			'type' => 'checkbox',
			'style' => null
		);
	}

	/**
	 * setter for is selected
	 *
	 * @param boolean $isSelected
	 * @return Checkbox
	 */
	public function setIsChecked($isChecked = true){
		$this->set('checked', true === $isChecked? 'checked' : null);
		return $this;
	}

	/**
	 * shortcut for setIsChecked
	 *
	 * @param boolean $isChecked
	 * @return Checkbox
	 */
	public function setChecked($isChecked = true){
		return $this->setIsChecked();
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return Checkbox
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
		HTML::renderCheckbox($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}