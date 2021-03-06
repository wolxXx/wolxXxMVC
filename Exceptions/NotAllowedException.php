<?
/**
 * exception for not allowed actions
 *
 * @author wolxXx
 * @version 1.2
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class NotAllowedException extends AuthException{
	/**
	 * overwrites default constructor
	 */
	public function __construct(){
		Helper::addSplash(Translator::translate('Diese Seite ist für dich nicht bestimmt!'));
		Helper::redirect('/error/403');
	}
}