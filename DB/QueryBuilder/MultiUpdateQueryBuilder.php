<?
/**
 * query builder for a multiple update query string
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class MultiUpdateQueryBuilder extends QueryBuilder{
	/**
	 * the data that should be set
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the conditions which items should be updated
	 *
	 * @var array
	 */
	protected $conditions;

	/**
	 * an instance of the DatabaseManager
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
		$this
			->setTable($table)
			->setConditions($conditions)
			->setData($data);
		$this->databaseManager = DatabaseManager::getInstance();
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return MultiUpdateQueryBuilder
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

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return MultiUpdateQueryBuilder
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(null === $this->conditions){
			throw new QueryGeneratorException('please specify conditions');
		}

		if(true === empty($this->conditions)){
			throw new QueryGeneratorException('please specify conditions');
		}

		if(null === $this->data){
			throw new QueryGeneratorException('please specify data');
		}

		if(true === empty($this->data)){
			throw new QueryGeneratorException('please specify data');
		}

		if(null === $this->table){
			throw new QueryGeneratorException('please specify table');
		}

		if('' === $this->table){
			throw new QueryGeneratorException('please specify table');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$where = $this->generateWhere();

		$settext = '';
		foreach ($this->data as $key => $value){
			$settext.= "`$key` = '".$this->databaseManager->getConnection()->escape($value)."', ";
		}
		$settext = rtrim($settext, ' ,');

		$query = "\nUPDATE\n\t`".$this->table."`\nSET\n\t$settext\nWHERE\n\t".$where."\n;";
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		$query = $this->generateQuery();
		$queryString = new QueryString();
		$queryString->setQueryString($query);
		return $queryString;
	}
}