<?
/**
 * it clears open grid divs
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Clear extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderClear();
		return $this;
	}
}