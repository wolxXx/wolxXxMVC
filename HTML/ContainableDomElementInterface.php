<?
/**
 * interface for html elements that can contain other elements
 *
 * @author wolxXx
 * @version 0.3
 * @package wolxXxMVC
 * @subpackage HTML
 */
interface ContainableDomElementInterface{
	/**
	 * adds a child to the element
	 *
	 * @return ContainableDomElementAbstract
	 */
	public function addChild(DomElementInterface $child);

	/**
	 * adds children to the children array
	 *
	 * @param array $children
	 * @return ContainableDomElementAbstract
	 */
	public function addChildren($array);

	/**
	 * returns all children
	 *
	 * @return array
	 */
	public function getChildren();
}