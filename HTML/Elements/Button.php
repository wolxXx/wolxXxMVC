<?
/**
 * a button element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Button extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
		);
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return Button
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderButton($this->getData());
		return $this;
	}
}