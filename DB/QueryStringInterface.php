<?
/**
 * the core model accepts only a query string
 * that was made by query builders
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
interface QueryStringInterface{
	/**
	 * getter for the query string
	 *
	 * @return string
	 * @param boolean $cleared
	 */
	public function getQueryString($cleared = false);

	/**
	 *constructor
	 *
	 * @param string $queryString
	 */
	public function __construct($queryString = null);

	/**
	 * setter for the query string
	 *
	 * @param string
	 */
	public function setQueryString($string);
}