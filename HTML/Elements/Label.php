<?
/**
 * a label element
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*
*/
class Label extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'for' => 'text',
			'text' => 'foo!',
		);
	}

	/**
	 * overwrites the default setLabel method
	 * because who needs a label for a label for a label? :)
	 * (non-PHPdoc)
	 * @see DomElementAbstract::setLabel()
	 */
	function setLabel(Label $label){
		return $this;
	}

	/**
	 * overwrites the default setLabel method
	 * because who needs a label for a label for a label? :)
	 * (non-PHPdoc)
	 * @see DomElementAbstract::addLabel()
	 */
	function addLabel($label = null){
		return $this;
	}

	/**
	 * setter for the for property
	 *
	 * @param string $for
	 * @return Label
	 */
	public function setFor($for){
		$this->data->set('for', $for);
		return $this;
	}

	/**
	 * setter for the text property
	 *
	 * @param string $text
	 * @return Label
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
		HTML::renderLabel($this->data->getData());
		return $this;
	}
}