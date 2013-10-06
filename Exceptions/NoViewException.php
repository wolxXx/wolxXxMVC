<?
/**
 * exception for not found views
 *
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class NoViewException extends Exception{
	public function __construct(){
		Stack::getInstance()->set('type', '404');
		Load::getInstance()->set('type', '404');
	}
}