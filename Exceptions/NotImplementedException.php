<?
/**
 * Exception for saying sorry, this is not implemented yet.
 * 
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
class NotImplementedException extends Exception{
	public function __construct(){
		Helper::logToFile($this->getMessage(), 'todo');
		Helper::addSplash('Entschuldigung, diese Funktionalität wurde noch nicht umgesetzt!');
		Helper::historyBack();
	}
}