<?
<<<<<<< HEAD
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
=======
class RadioOption extends DomElementAbstract{
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public static function getDefaultConf(){
		return array(
			'checked' => null
		);
	}

<<<<<<< HEAD
	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return RadioOption
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

<<<<<<< HEAD
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function display(){
		$this->displayLabelBefore();
		HTML::renderRadioOption($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}