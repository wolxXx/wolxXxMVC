<?
/**
 * query builder for deleting items in the database
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class MultiDeleteQueryBuilder extends QueryBuilder{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * an instance of the DatabaseManager
	 *
	 * @var DatabaseManager
	 */
	protected $databaseManager;

	/**
	 * the conditions array
	 *
	 * @var array
	 */
	protected $conditions;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param array $conditions
	 */
	public function __construct($table = null, $conditions = null){
		$this
			->setTable($table)
			->setConditions($conditions);
		$this->databaseManager = DatabaseManager::getInstance();
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	public function checkConditions(){
		if(null === $this->conditions){
			throw new QueryGeneratorException('please specify conditions');
		}

		if(true === empty($this->conditions)){
			throw new QueryGeneratorException('please specify conditions');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$where = $this->generateWhere();
		$query = "DELETE FROM `".$this->table."` WHERE ".$where.";";
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		return new QueryString($this->generateQuery());
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return MultiDeleteQueryBuilder
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::setConditions()
	 */
	public function setConditions($conditions){
		$this->conditions = array('where' => $conditions);
		return $this;
	}
}