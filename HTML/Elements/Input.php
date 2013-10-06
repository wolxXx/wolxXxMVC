<?
/**
 * a input element
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*/
class Input extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'type' => 'text',
			'value' => null,
			'autocomplete' => null,
			'readonly' => null
		);
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return Input
	 */
	public function setValue($value){
		$this->data->set('value', $value);
		return $this;
	}

	/**
	 * setter for the type
	 *
	 * @param string $type
	 * @return Input
	 */
	public function setType($type){
		$this->set('type', $type);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		HTML::renderInput($this->getData());
		$this->displayLabelAfter();
		return $this;
	}
}