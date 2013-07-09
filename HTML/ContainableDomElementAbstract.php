<?
/**
 * abstract class for providing containable elements
 *
 * @author wolxXx
 * @version 0.3
 * @package wolxXxMVC
 * @subpackage HTML
 */
abstract class ContainableDomElementAbstract extends DomElementAbstract implements ContainableDomElementInterface{
	/**
	 * elements in the grid, textareas, inputs, etc
	 *
	 * @var array
	 */
	protected $children = array();

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::addChild()
	 */
	public function addChild(DomElementInterface $child){
		$this->children[] = $child;
		return $this;
	}

	/**
<<<<<<< HEAD
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::addChildren()
=======
	 * adds children to the children array
	 *
	 * @param array $children
	 * @return ContainableDomElementAbstract
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
	 */
	public function addChildren($children = array()){
		foreach($children as $current){
			$this->addChild($current);
		}
		return $this;
	}
<<<<<<< HEAD

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::getChildren()
	 */
	public function getChildren(){
		return $this->children;
	}
=======
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
}