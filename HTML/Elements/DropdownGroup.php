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
<<<<<<< HEAD
			'label' => null
=======
<<<<<<< HEAD
			'label' => null
=======
			'label' => ''
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
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