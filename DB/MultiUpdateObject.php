<?
/**
 * class for updating multiple items in the database
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class MultiUpdateObject{
	/**
	 * the name of the table where the items lies
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the data that should be written to the database
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * the conditions which items should the updated
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
	 * @param array $data
	 * @param array $conditions
	 */
	public function __construct($table = null, $data = null, $conditions = null){
		$this->databaseManager = DatabaseManager::getInstance();
		$this
			->setTable($table)
			->setConditions($conditions)
			->setData($data);
	}

	/**
	 * setter for values that should be set for the fields
	 *
	 * @param string $key
	 * @param any $value
	 * @return MultiUpdateObject
	 */
	public function __set($key, $value){
		$this->addData($key, $value);
		return $this;
	}

	/**
	 * adds key and value that should be set for the fields
	 *
	 * @param string $key
	 * @param any $value
	 * @return MultiUpdateObject
	 */
	public function addData($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * creates a query builder and sends the query to the database manager
	 *
	 * @return QueryResultObject
	 */
	public function update(){
		$queryBuilder = new MultiUpdateQueryBuilder();
		$queryBuilder
			->setConditions($this->conditions)
			->setTable($this->table)
			->setData($this->data);
		$queryString = $queryBuilder->getQueryString();
		return $this->databaseManager->update($queryString);
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return MultiUpdateObject
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the conditions array
	 *
	 * @param array $conditions
	 * @return MultiUpdateObject
	 */
	public function setConditions($conditions){
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * adds a condition to the already set conditions
	 *
	 * @param array $conditions
	 * @return MultiUpdateObject
	 */
	public function addConditions($conditions){
		$this->conditions = array_merge_recursive($this->conditions, $conditions);
		return $this;
	}

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return MultiUpdateObject
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}
}