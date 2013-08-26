<?
/**
 * a radio element container
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Radio extends ContainableDomElementAbstract{
	/**
	 * overwrites the abstract method
	 * only radio options are allowed as children
	 *
	 * @param RadioOption
	 * @return Radio
	 * @throws Exception
	 */
	public function addChild(DomElementInterface $child){
		if($child instanceof RadioOption){
			$child->setName($this->get('name'));
			parent::addChild($child);
			return $this;
		}
		throw new Exception('radio elements can only contain radio options as children');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		foreach($this->children as $current){
			$current->display();
		}
		return $this;
	}
}