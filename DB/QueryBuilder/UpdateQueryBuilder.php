<?
/**
 * creates an update query string
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class UpdateQueryBuilder{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the data array
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * the id of the element that should be updated
	 *
	 * @var integer
	 */
	protected $rowId;

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
	 * @param integer $id
	 */
	public function __construct($table = null, $data = null, $rowId = null){
		$this
			->setTable($table)
			->setData($data)
			->setId($rowId);
		$this->databaseManager = DatabaseManager::getInstance();
	}

	/**
	 * checks the set conditions
	 *
	 * @throws QueryGeneratorException
	 */
	protected function checkConditions(){
		if(null === $this->table){
			throw new QueryGeneratorException('please specify table');
		}

		if('' === $this->table){
			throw new QueryGeneratorException('please specify table');
		}

		if(null === $this->rowId){
			throw new QueryGeneratorException('please specify id');
		}

		if(false === is_array($this->data)){
			throw new QueryGeneratorException('data should be an array!');
		}

		if(true === empty($this->data)){
			throw new QueryGeneratorException('data should contain data');
		}
	}

	/**
	 * creates the query
	 *
	 * @return string
	 */
	public function getQuery(){
		$this->checkConditions();

		$settext = '';
		foreach ($this->data as $key => $value){
			$settext.= "`$key` = '".$this->databaseManager->getConnection()->escape($value)."', ";
		}
		$settext = rtrim($settext, ' ,');
		$query = "\nUPDATE\n\t`".$this->table."`\nSET\n\t$settext\nWHERE\n\t`".$this->table."`.id = $this->rowId\n;";
		return $query;
	}

	/**
	 * creates an instance of a QueryString
	 *
	 * @return QueryString
	 */
	public function getQueryString(){
		return new QueryString($this->getQuery());
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return UpdateQueryBuilder
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return UpdateQueryBuilder
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * setter for the item id
	 *
	 * @param integer $id
	 * @return UpdateQueryBuilder
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}
}