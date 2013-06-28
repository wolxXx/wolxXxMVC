<?
/**
 * a password input element
*
* @author wolxXx
* @version 0.2
* @package wolxXxMVC
* @subpackage HTML
*
*/
class Password extends DomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'name' => 'password'
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		if(null !== $this->label){
			$this->label->display();
		}
		HTML::renderPassword($this->data->getData());
		return $this;
	}
}