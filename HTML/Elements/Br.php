<?
<<<<<<< HEAD
/**
 * displays a break element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Br extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
=======
class Br extends DomElementAbstract{
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public static function getDefaultConf(){
		return array(
		);
	}

<<<<<<< HEAD
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function display(){
		HTML::renderBr();
	}
}