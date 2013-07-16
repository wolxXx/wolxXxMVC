<?
/**
 * plaintext
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*/
class Plaintext extends DomElementAbstract{
	/**
	 * adds text to the given text
	 * or creates one if nothing was set before
	 *
	 * @param string $text
	 * @return Plaintext
	 */
	public function addText($text){
		if(false === $this->data->hasKey('text')){
			return $this->set('text', $text);
		}
		return $this->set('text', $this->get('text').' '.$text);
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return Plaintext
	 */
	public function setText($text){
		return $this->set('text', $text);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		echo $this->get('text');
		return $this;
	}
}