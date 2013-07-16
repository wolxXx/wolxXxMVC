<?
/**
 * a label element
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*/
class Label extends DomElementAbstract{
	/**
	 * position of the label, can be before or after
	 *
	 * @var string
	 */
	protected $position = 'before';

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
	 * setter for the position of the element
	 * can be before or after
	 *
	 * @param string $position
	 * @return Label
	 */
	public function setPosition($position = null){
		$this->position = 'before' === $position? 'before' : 'after';
		return $this;
	}

	/**
	 * returns the wanted position
	 *
	 * @return string
	 */
	public function getPosition(){
		return $this->position;
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
	function addLabel($label = null, $position = 'before'){
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