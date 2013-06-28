<?
/**
 * abstract class for providing containable elements
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 *
 */
abstract class ContainableDomElementAbstract extends DomElementAbstract implements ContainableDomElementInterface{
	/**
	 * elements in the grid, textareas, inputs, etc
	 *
	 * @var array
	 */
	protected $children = array();

	/**
	 * adds a child to the form
	 *
	 * @param DomElementInterface $child
	 * @return ContainableDomElementAbstract
	 */
	public function addChild(DomElementInterface $child){
		$this->children[] = $child;
		return $this;
	}
}