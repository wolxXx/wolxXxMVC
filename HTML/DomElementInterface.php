<?
/**
 * interface for html elements
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 *
 */
interface DomElementInterface{
	/**
	 * calls the html element generator
	 *
	 * @return FormElementInterface
	 */
	public function display();

	/**
	 * returns the default config for this element
	 *
	 * @return array
	 */
	public static function getDefaultConf();
}