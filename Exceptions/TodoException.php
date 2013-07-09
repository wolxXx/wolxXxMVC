<?
/**
 * exception for not implemented functionality
 *
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class TodoException extends Exception{
	/**
	 * overwrites default constructor
	 */
	public function __construct(){
		Helper::logToFile($this->getMessage(), 'todo');
		Helper::addSplash(Translator::translate('Entschuldigung, diese Funktionalität wurde noch nicht umgesetzt!'));
		Helper::historyBack();
	}
}