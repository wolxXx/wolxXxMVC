<?
/**
 * interface for html elements
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
interface DomElementInterface{
	/**
	 * calls the html element generator
	 *
	 * @return DomElementInterface
	 */
	public function display();

	/**
	 * returns the default config for this element
	 *
	 * @return array
	 */
	public static function getDefaultConf();

	/**
	 * returns the ID of the element
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * returns the name of the element
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * setter for required flag
	 *
	 * @param boolean $required
	 * @return DomElementInterface
	 */
	public function setIsRequired($required = true);
}