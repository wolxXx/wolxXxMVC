<?
/**
 * exception for needed auth
 *
 * @author wolxXx
 * @version 1.2
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class AuthRequestedException extends AuthException{
	/**
	 * overwrites default constructor
	 */
	public function __construct(){
		Stack::getInstance()->set('redirect', Helper::getCurrentURI());
		Helper::redirect('/auth/login');
	}
}