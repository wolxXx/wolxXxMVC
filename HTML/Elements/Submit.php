<?
/**
 * a submit button element
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*/
class Submit extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'name' => null,
			'value' => Translator::translate('abschicken'),
			'type' => 'submit'
		);
	}

	/**
	 * removes the name property as default behaviour
	 * but the property can be set later if wanted!
	 * (non-PHPdoc)
	 * @see DomElementAbstract::init()
	 */
	public function init(){
		parent::init();
		$this->data->removeData('name');
	}

	/**
	 * sets the value of this button
	 *
	 * @param string $value
	 * @return Submit
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
		HTML::renderSubmit($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}