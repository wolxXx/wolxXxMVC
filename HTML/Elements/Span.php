<?
/**
 * a span element
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
class Span extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'class' => null
		);
	}

	/**
<<<<<<< HEAD
	 * setter for the text
	 *
	 * @param string $text
	 * @return Span
	 */
	public function setText($text){
		$this->data->set('text', $text);
		return $this;
	}

	/**
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderSpan($this->data->getData());
		return $this;
	}
}