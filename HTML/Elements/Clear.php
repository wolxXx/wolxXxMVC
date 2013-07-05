<?
/**
<<<<<<< HEAD
 * it clears open grid divs
=======
 * a clears open grids
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
<<<<<<< HEAD
=======
 *
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
 */
class Clear extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderClear();
		return $this;
	}
}