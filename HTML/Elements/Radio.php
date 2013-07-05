<?
<<<<<<< HEAD
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
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
=======
class Radio extends ContainableDomElementAbstract{
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public static function getDefaultConf(){
		return array(
		);
	}

<<<<<<< HEAD
	/**
	 * overwrites the abstract method
	 * only radio options are allowed as children
	 *
	 * @param RadioOption
	 * @return Radio
	 * @throws Exception
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function addChild(DomElementInterface $child){
		if($child instanceof RadioOption){
			$child->setName($this->get('name'));
			parent::addChild($child);
			return $this;
		}
		throw new Exception('radio elements can only contain radio options as children');
	}

<<<<<<< HEAD
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function display(){
		foreach($this->children as $current){
			$current->display();
		}
		return $this;
	}
}