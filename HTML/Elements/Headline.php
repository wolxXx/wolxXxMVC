<?
/**
 * a headline element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Headline extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'size' => 1,
			'text' => ''
		);
	}

	/**
	 * setter for the size
	 *
	 * @param integer $size
	 * @return Headline
	 */
	public function setSize($size){
		$this->set('size', $size);
		return $this;
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return Headline
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		HTML::renderHeadline($this->data->getData());
		return $this;
	}
}