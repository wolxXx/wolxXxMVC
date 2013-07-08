<?
/**
 * Exception for saying sorry, this is not implemented yet.
 *
 * @author wolxXx
 * @version 1.2
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class NotImplementedException extends Exception{
	/**
	 * overwrites default constructor
	 */
	public function __construct(){
		Helper::logToFile($this->getMessage(), 'todo');
		Helper::addSplash(Translator::translate('Entschuldigung, diese Funktionalität wurde noch nicht umgesetzt!'));
		Helper::historyBack();
	}
}