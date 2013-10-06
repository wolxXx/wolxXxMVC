<?
/**
 * factory class for database objects
 * currently supported: save, update, delete, multiDelete
 *
 * @author wolxXx
 * @version 0.1
 * @package wolxXxMVC
 * @subpackage Database
 *
 */
class DatabaseObjectFactory{
	/**
	 * creates a new model object
	 *
	 * @param string $type
	 * @throws Exception
	 * @return CoreModel
	 */
	public static function getModel($type = ''){
		$requested = $type.'Model';
		if(true === AutoLoader::isLoadable($requested)){
			return new $requested();
		}
		throw new Exception('cannot load '.$requested);
	}
	/**
	 * creates a new save object
	 *
	 * @param string $table
	 * @return SaveObject
	 */
	public static function getSaveObject($table){
		return new SaveObject($table);
	}

	/**
	 * creates a new update object
	 *
	 * @param string $table
	 * @param integer $rowId
	 * @return UpdateObject
	 */
	public static function getUpdateObject($table = null, $rowId= null){
		return new UpdateObject($table, $rowId);
	}

	/**
	 * creates a new delete object
	 *
	 * @param string $table
	 * @param integer $rowId
	 * @return DeleteObject
	 */
	public static function getDeleteObject($table = null, $rowId= null){
		return new DeleteObject($table, $rowId);
	}

	/**
	 * creates a new multi delete object
	 *
	 * @param string $table
	 * @param array $conditions
	 * @return MultiDeleteObject
	 */
	public static function getMultiDeleteObject($table = null, $conditions = array()){
		return new MultiDeleteObject($table, $conditions);
	}

	/**
	 * creates a new multi update object
	 *
	 * @param string $table
	 * @param array $data
	 * @param array $conditions
	 * @return MultiUpdateObject
	 */
	public static function getMultiUpdateObject($table = null, $data = null, $conditions = null){
		return new MultiUpdateObject($table, $data, $conditions);
	}
}