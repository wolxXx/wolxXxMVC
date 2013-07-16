<?
/**
 * a dropdown element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class DropdownElement extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'selected' => null
		);
	}

	/**
	 * setter for is selected
	 *
	 * @param boolean $isSelected
	 * @return DropdownElement
	 */
	public function setIsSelected($isSelected = true){
		$this->set('selected', true === $isSelected? 'selected' : null);
		return $this;
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return DropdownElement
	 */
	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return DropdownElement
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}

	/**
	 * sets value and text
	 *
	 * @param string $value
	 * @return DropdownElement
	 */
	public function setValueAndText($value){
		return $this
			->setText($value)
			->setValue($value)
		;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderOption($this->data->getData());
		return $this;
	}
}