<?
/**
 * query builder for deleting a single item in the database
 * the item is identified via its primary key which should be "id"
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class DeleteQueryBuilder extends QueryBuilder{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * an instance of the database manager
	 *
	 * @var DatabaseManager
	 */
	protected $databaseManager;

	/**
	 * the id of the item that should be deleted
	 *
	 * @var integer
	 */
	protected $rowId;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param integer $id
	 * @param DatabaseManager $databaseManager
	 */
	public function __construct($table = null, $rowId = null, $databaseManager = null){
		$this
			->setId($rowId)
			->setDatabaseManager($databaseManager)
			->setTable($table);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(true === false){
			throw new ApocalypseException('gehnwa bierchen trinken. bringt nix mehr.');
		}
		if(null === $this->table){
			throw new QueryGeneratorException('please specify table');
		}
		if(null === $this->databaseManager){
			throw new QueryGeneratorException('please specify databaseManager');
		}
		if(null === $this->rowId){
			throw new QueryGeneratorException('please specify id');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$query = "DELETE FROM `".$this->table."` WHERE `".$this->table."`.id = ".$this->rowId." LIMIT 1;";
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		$query = $this->generateQuery();
		$queryString = new QueryString($query);
		return $queryString;
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return DeleteQueryBuilder
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the item id
	 *
	 * @param integer $id
	 * @return DeleteQueryBuilder
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}

	/**
	 * setter for the databaseManager
	 *
	 * @param DatabaseManager $databaseManager
	 * @return DeleteQueryBuilder
	 */
	public function setDatabaseManager($databaseManager){
		$this->databaseManager = $databaseManager;
		return $this;
	}

}