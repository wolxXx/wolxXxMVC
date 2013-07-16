<?
/**
 * a dropdown group element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class DropdownGroup extends ContainableDomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'label' => null
		);
	}

	/**
	 * overwrites the abstract method
	 * it only accepts dropdown elements
	 *
	 * @param DropdownElement
	 * @return DropdownGroup
	 * @throws Exception
	 */
	public function addChild(DomElementInterface $child){
		if($child instanceof DropdownElement){
			parent::addChild($child);
			return $this;
		}
		throw new Exception('dropdown group container can only contain dropdown elements as children');
	}

	/**
	 * overwrites the abstract method
	 * because label is something different in this context
	 * as <optgroup label="foo"> is not <label>foo</label> <optgroup>
	 *
	 * @return DropdownGroup
	 */
	public function addLabel($text = null, $position = 'before'){
		$this->set('label', $text);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderOptionGroupStart($this->data->getData());
		foreach($this->children as $current){
			$current->display();
		}
		HTML::renderOptionGroupClose();
		return $this;
	}
}