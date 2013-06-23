<?
/**
 * exception for needed auth
 *
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class AuthRequestedException extends AuthException{
	/**
	 * overwrites default constructor
	 */
	public function __construct(){
		$stack = Stack::getInstance();
		$stack->set('redirect', $_SERVER['REQUEST_URI']);
		Helper::redirect('/auth/login');
	}
}