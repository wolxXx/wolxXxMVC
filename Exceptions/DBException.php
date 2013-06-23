<?
/**
 * exception for database errors
 * 
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class DBException extends Exception{
	/**
	 * overwrites default constructor
	 * @param string $message
	 */
	public function __construct($message){
		Helper::logerror($message);
	}
}