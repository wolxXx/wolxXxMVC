<?
/**
 * a textara element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Textarea extends ContainableDomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'rows' => '1000',
			'cols' => '1000',
			'text' => ''
		);
	}

	/**
	 * sets the text for the textarea
	 *
	 * @param string $text
	 * @return Textarea
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}

	/**
	 * setter for the rows
	 *
	 * @param string $rows
	 * @return Textarea
	 */
	public function setRows($rows){
		$this->set('rows', $rows);
		return $this;
	}

	/**
	 * setter for the cols
	 *
	 * @param string $cols
	 * @return Textarea
	 */
	public function setCols($cols){
		$this->set('cols', $cols);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		HTML::renderTextarea($this->data->getData());
		$this->displayLabelAfter();
		return $this;
	}
}