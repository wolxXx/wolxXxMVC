<?
/**
 * class for deleteing just one item from the databae
 * 
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class DeleteObject{
	/**
	 * the name of the table
	 * @var string
	 */
	protected $table;
	
	/**
	 * the id of the item 
	 * @var integer
	 */
	protected $id;
	
	/**
	 * an instance of the DatabaseManager
	 * @var DatabaseManager
	 */
	protected $databaseManager;
	
	/**
	 * constructor
	 * @param string $table
	 * @param integer $id
	 */
	public function __construct($table, $id){
		$this
			->setId($id)
			->setTable($table);
		$this->databaseManager = DatabaseManager::getInstance();
	}
	
	/**
	 * deletes the specified item
	 * @return QueryResultObject
	 */
	public function delete(){
		$queryBuilder = new DeleteQueryBuilder($this->table, $this->id, $this->databaseManager);
		return $this->databaseManager->delete($queryBuilder->getQueryString());
	}
	
	/**
	 * setter for the table name
	 * @param string $table
	 * @return DeleteObject
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}
	
	/**
	 * setter for the item id
	 * @param integer $id
	 * @return DeleteObject
	 */
	public function setId($id){
		$this->id = $id;
		return $this;
	}
}