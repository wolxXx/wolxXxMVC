<?
/**
 * a span element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
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
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderSpan($this->getData());
		return $this;
	}
}