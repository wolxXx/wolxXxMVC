<?
/**
 * class for deleting multiple items from a database table
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class MultiDeleteObject{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the conditions, which items should be deleted
	 *
	 * @var array
	 */
	protected $conditions;

	/**
	 * an instance of the databaseManager
	 *
	 * @var DatabaseManager
	 */
	protected $databaseManager;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param array $conditions
	 */
	public function __construct($table = null, $conditions = array()){
		$this->databaseManager = DatabaseManager::getInstance();
		$this
			->setConditions($conditions)
			->setTable($table);
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return MultiDeleteObject
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the conditions array
	 *
	 * @param array $conditions
	 * @return MultiDeleteObject
	 */
	public function setConditions($conditions){
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * sends conditions and table to querybuilder and sends the query to the database manager
	 *
	 * @return QueryResultObject
	 */
	public function delete(){
		$queryBuilder = new MultiDeleteQueryBuilder();
		$queryBuilder
			->setConditions($this->conditions)
			->setTable($this->table);
		$queryString = $queryBuilder->getQueryString();
		return $this->databaseManager->delete($queryString);
	}

}