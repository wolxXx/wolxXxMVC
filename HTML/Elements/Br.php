<?
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
		);
	}

	/**
	 * (non-PHPdoc)
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderBr();
	}
}