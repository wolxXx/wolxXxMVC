<?
/**
 * a dropdown element container
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Dropdown extends ContainableDomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
		);
	}

	/**
	 * overwrites the abstract method
	 * it only accepts dropdown elements or dropdown groups
	 *
	 * @param DropdownElement | DropdownGroup
	 * @return Dropdown
	 * @throws Exception
	 */
	public function addChild(DomElementInterface $child){
		if($child instanceof DropdownElement || $child instanceof DropdownGroup){
			parent::addChild($child);
			return $this;
		}
		throw new Exception('dropdown container can only contain dropdown elements as children');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public function display(){
		$this->displayLabelBefore();
		HTML::renderSelectStart($this->getData());
		foreach($this->children as $current){
			$current->display();
		}
		HTML::renderSelectClose();
		$this->displayLabelAfter();
		return $this;
	}
}