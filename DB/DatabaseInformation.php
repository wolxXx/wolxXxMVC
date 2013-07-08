<?
/**
 * retrieves information from mysql databases, tables and columns
 * requires an instanciated model object
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class DatabaseInformation{
	/**
	 * retrieves all columns for a table
	 *
	 * @param string $databaseName
	 * @param string $tableName
	 * @return array
	 */
	public static function getColumnsForTable($databaseName, $tableName){
		$model = new Model();
		return $model->find(array(
			'distinct' => true,
			'method' => 'all',
			'from' => 'information_schema.COLUMNS',
			'fields' => '*',
			'where' => array(
				'TABLE_SCHEMA' => $databaseName,
				'TABLE_NAME' => $tableName
			)
		));
	}

	/**
	 * retrieves the the of a column in a table
	 *
	 * @param string $databaseName
	 * @param string $tableName
	 * @param string $columnName
	 * @return stdClass
	 */
	public static function getTypeOfColumn($databaseName, $tableName, $columnName){
		$model = new Model();
		return $model->find(array(
			'distinct' => true,
			'method' => 'one',
			'from' => 'information_schema.COLUMNS',
			'fields' => array(
				'DATA_TYPE'
			),
			'where' => array(
				'TABLE_SCHEMA' => $databaseName,
				'TABLE_NAME' => $tableName,
				'COLUMN_NAME' => $columnName
			)
		));
	}

	/**
	 * retrieves all tables in a database
	 * exclude array may contain some strings for excluding matching table names
	 *
	 * @param string $databaseName
	 * @param array $exclude
	 * @return array
	 */
	public static function getAllTables($databaseName, $exclude = array()){
		$model = new Model();
		return $model->find(array(
			'method' => 'all',
			'distinct' => true,
			'fields' => array(
				'TABLE_NAME'
			),
			'from' => 'information_schema.COLUMNS',
			'where' => array(
				'TABLE_SCHEMA' => $databaseName,
				'NOTIN' => array(
					'TABLE_NAME' => $exclude
				)
			)
		));
	}
}