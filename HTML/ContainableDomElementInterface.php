<?
/**
 * interface for html elements that can contain other elements
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 *
 */
interface ContainableDomElementInterface{
	/**
	 * calls the html element generator
	 *
	 * @return FormElementInterface
	 */
	public function addChild(DomElementInterface $child);
}