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
<<<<<<< HEAD
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::addChildren()
=======
	 * adds children to the children array
	 *
	 * @param array $children
	 * @return ContainableDomElementAbstract
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
	 */
	public function addChildren($children = array()){
		foreach($children as $current){
			$this->addChild($current);
		}
		return $this;
	}
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::getChildren()
	 */
	public function getChildren(){
		return $this->children;
	}
<<<<<<< HEAD
=======
=======
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
}