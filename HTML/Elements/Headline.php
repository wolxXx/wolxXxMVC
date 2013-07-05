<?
<<<<<<< HEAD
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
=======
class Headline extends DomElementAbstract{
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public static function getDefaultConf(){
		return array(
			'size' => 1,
			'text' => ''
		);
	}

<<<<<<< HEAD
	/**
	 * setter for the size
	 *
	 * @param integer $size
	 * @return Headline
	 */
=======
>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function setSize($size){
		$this->set('size', $size);
		return $this;
	}

<<<<<<< HEAD
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
=======
	public function setText($text){
		$this->set('text', $text);
	}

>>>>>>> aff1b8d8aa3d5064fa17e1ed831c087c732905cc
	public function display(){
		HTML::renderHeadline($this->data->getData());
		return $this;
	}
}