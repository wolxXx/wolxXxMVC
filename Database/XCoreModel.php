<?
/**
 * ultra fetter unterbau des models, alta!
 * 
 * in fact, this class does everything about database access. 
 * it connects to a running mysql database. 
 * it requires having mysqli installed and enabled for php
 * 
 * it can find, update, save and delete data
 * for finding, there is a nice functionality that allows you providing an array filled with conditions and constraits
 * but you can provide just a mysql query string as well as the find method does not provide the whole mysql query functionality
 * it is built up as a singleton class so yout can get access to this class via the public static function getInstance
 * 
 * @author wolxXx
 * @version 1.7
 * @package wolxXxMVC
 *
 */
abstract class XCoreModel{
	/**
	 * instance in pattern of singleton of this class
	 * @var CoreModel
	 */
	private static $instance = null;
	/**
	 *
	 * database connection
	 * @var Connection
	 */
	private $connection = null;
	/**
	 *
	 * database description and info
	 * @var Connection
	 */
	private $dbinfo = null;
	/**
	 *
	 * global stack access
	 * @var Stack
	 */
	protected $stack = null;
	/**
	 * forces logging
	 * @var boolean 
	 */
	protected $forceLog = false;
	
	/**
	 * latest set conditions
	 * @var array
	 */
	protected $conditions = null;
	
	/**
	 * latest built query 
	 * @var string
	 */
	protected $lastQuery = null;
	
	/**
	 * constructor
	 * creates Connection instances
	 * credentials come out of the stack fields db_host, db_user, db_pass, db_table
	 */
	private function __construct(){
		$this->connection = new Connection();
		$this->connection
			->setHost(Stack::getIntance()->get('db_host'))
			->setUser(Stack::getIntance()->get('db_user'))
			->setPassword(Stack::getIntance()->get('db_pass'))
			->setDatabase(Stack::getIntance()->get('db_table'))	
			->connect();
		

		$this->dbinfo = new Connection();
		$this->dbinfo
			->setHost(Stack::getIntance()->get('db_host'))
			->setUser(Stack::getIntance()->get('db_user'))
			->setPassword(Stack::getIntance()->get('db_pass'))
			->setDatabase(Stack::getIntance()->get('information_schema'))	
			->connect();
		
		Stack::getIntance()->set('db_host', null);
		Stack::getIntance()->set('db_user', null);
		Stack::getIntance()->set('db_pass', null);
	}

	/**
	 *
	 * get the only one instance!
	 * it will create an instance if none was created before
	 * as long as this is an abstract class, this returns an instanctiated Model object 
	 * @return Model
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new Model();
		}
		return self::$instance;
	}
	
	/**
	 * escapes string
	 * @param string $value
	 * @return string
	 */
	public function escape($value){
		Helper::logDeprecatedAccess();
		return $this->connection->escape($value);
	}
	
	/**
	 * returnes a query built for an array filled with conditions
	 * @param array $conditions
	 * @throws QueryGeneratorException
	 * @return string
	 * @deprecated
	 */
	public function getFindQuery($conditions){
		Helper::logDeprecatedAccess();
		$queryBuilder = new SelectQueryBuilder();
		$queryBuilder->setConditions($conditions)->setConnection($this->getConnection());
		return $queryBuilder->generateQuery();
	}

	
	
	/**
	 * the core finding methos
	 * @param array $conditions
	 * @throws Exception
	 */
	public function find($conditions = array()){
		$queryBuilder = new SelectQueryBuilder();
		$queryBuilder->setConditions($conditions);
		$query = $queryBuilder->generateQuery();
		if(true === $queryBuilder->isQueryForAll()){
			return $this->queryForAll($query);
		}
		return $this->queryForOne($query);
	}
	
	/**
	 * sends the given query to the database, does some measuring and logging and returns the mysqli_ressource
	 * @param string $query
	 * @return resource
	 */
	public function query($query){
		$this->lastQuery = $query;		
		$start = microtime(true);
		$result = $this->connection->query($query);
		$end = microtime(true);
		$results = $result instanceof mysqli_result? $result->num_rows : 0;
		$error = $this->connection->getError();
		$this->log($query, $start, $end, $results, $error);
		return $result;
	}

	/**
	 * sends the given query and fetches the first result into an object
	 * @param string $query
	 * @return stdClass | null
	 */
	public function queryForOne($query){
		$result = $this->query($query);
		$ret = null;
		if(false !== $result && 0 !== $result->num_rows){
			$ret = $result->fetch_object();
			$result->close();
		}
		return $ret;
	}
	/**
	 * sends the given query and fetches all result objects into an array
	 * @param string $query
	 * @return array
	 */
	public function queryForAll($query){
		$result = $this->query($query);
		$ret = array();
		if(false !== $result && 0 !== $result->num_rows){
			while($obj = $result->fetch_object()){
				$ret[] = $obj;
			}
			$result->close();
		}
		return $ret;
	}
	/**
	 * logs a query
	 * @param string $query
	 * @param float $start
	 * @param float $end
	 * @param integer $results
	 * @param string $error
	 */
	private function log($query, $start, $end, $results, $error){
		if('' !== $error && false === $this->forceLog && '0' !== $this->stack->get('disable_db_log')){			
			return;
		}
		//grab some log data
		$error = '' === $error? '-none-' : $error;
		$execution = $end - $start;
		$user = Auth::isLoggedIn()? Auth::getUserNick() : 'arno nym';
		$remote_ip = isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : 'localhost';
		$date = Helper::getDate();
		$text = "
_________________________________

Query: $query
Results: $results
Execution: $execution
Error: $error
Date: $date
User: $user
IP: $remote_ip
";
		if(true == $this->getForceLog() || (int)$execution > 1){
			$file = fopen('log/dblog', 'a+');
			fputs($file, $text, strlen($text));
			fclose($file);
		}
		if('-none-' !== $error){
			$file = fopen('log/dberrorlog', 'a+');
			fputs($file, $text, strlen($text));
			fclose($file);
		}
		//reset forceLog, so side effects wont remain
		$this->forceLog = false;
	}
	
	/**
	 * returns force log flag
	 * @return boolean
	 */
	public function getForceLog(){
		return $this->forceLog;
	}
	
	/**
	 * sets force log flag
	 * @param boolean $set
	 */
	public function setForceLog($set = true){
		$this->forceLog = $set;
	}

	/**
	 * use with caution!
	 * this returns the database connection
	 * @return mysqli
	 */
	public function getConnection(){
		return $this->connection;
	}

	/**
	 *
	 * finds an entry in table by id, if field == null
	 * @param string $model
	 * @param string|array $key
	 * @param string $field
	 */
	public function findOne($model, $key, $field = null){
		if(null === $field){
			$field = 'id';
		}
		return $this->find(array(
			'from' => $model,
			'method' => 'one',
			'where' => array(
				$field => $key
			)
		));
	}
	
	/**
	 * retrieves the amount of results for a query
	 * @param array $conditions
	 * @return integer
	 */
	public function count($conditions){
		$conditions['method'] = null;
		$queryBuilder = new SelectQueryBuilder();
		$queryBuilder->setConditions($conditions);
		$query = $queryBuilder->generateQuery();
		$result = $this->queryForAll($query);
		return sizeof($result);
	}

	/**
	 * generates a inserting query
	 * accepts an array or an object for the data holding
	 * $model should be an existing database field, otherwise the query fails
	 *   
	 * @param string $model
	 * @param array | stdClass $object
	 * @throws Exception
	 * @return string
	 */
	public function getSaveQuery($model, $object){
		if(false === is_array($object) && false === is_object($object)){
			throw new Exception('could not create model if data is not array or simple object');
		}
		$keys = '';
		$values = '';
		if(true === is_array($object)){
			foreach(array_keys($object) as $key){
				$keys .= '`'.$key.'`,';
				$values .= "'".$this->escape($object[$key])."',";
			}
		}else{
			foreach(get_object_vars($object) as $key => $value){
				$keys .= '`'.$key.'`,';
				$values .= "'".$this->escape($value)."',";
			}
		}
		//eliminate the last ','
		$keys = rtrim($keys, ',');
		$values = rtrim($values, ',');
		//merge together
		$query = "INSERT INTO $model ($keys) VALUES ($values);";
		return $query;
	}
	
	
	/**
	 * creates an update query
	 * @param string $model
	 * @param array | stdClass $object
	 * @param string | integer $item_id
	 * @throws Exception
	 */
	public function getUpdateQuery($model, $object, $where){
		if(false === is_array($object) && false === is_object($object)){
			throw new Exception('update object type not supported - need array or stdClass');
		}
		$settext = '';
		if(true === is_array($object)){
			foreach ($object as $key => $value){
				$settext.= "`$key` = '".$this->escape($value)."', ";
			}
		}else{
			foreach(get_object_vars($object) as $key => $value){
				$settext .= "`$key` = '".$this->escape($value)."', ";
			}
		}
		$settext = rtrim($settext, ' ,');
		$query = "\nUPDATE\n\t`$model`\nSET\n\t$settext\nWHERE\n\t$where\n;";
		return $query;
	}
	
	/**
	 * creates a delete from query
	 * @param string $model
	 * @param string $where
	 * @return string
	 */
	public function getDeleteQuery($model, $where){
		$query = "DELETE FROM `$model` WHERE $where;";
		return $query;
	}

	/**
	 *
	 * give me an array or a simple object, i will get through the keys of it and create an insert-statement
	 * the model-string names the table in db
	 * @param string $model
	 * @param array|SimpleObject $object
	 * @return boolean for successfull queried / inserted
	 */
	public function save(SaveObject $object){
		$queryBuilder = new InsertQueryBuilder($object->getTableName(), $object->getSaveData());
		$query = $queryBuilder->generateQuery();
		$result = $this->query($query);
		return new ResultObject($result, $query, $this->getLastError(), $this->getLastInsertId());
	}
	
	/**
	 *
	 * give me an array or a simple object, i will update the referenced model
	 * the model-string names the table in db
	 * @param string $model
	 * @param array|stdClass $object
	 * @param integer $id
	 * @return boolean for successfull query
	 */
	public function update($model, $object, $item_id){
		$where = '`'.$model.'`.`id` = '.$item_id;
		$query = $this->getUpdateQuery($model, $object, $where);
		$result = $this->query($query);
		return new ResultObject($result, $query, $this->getLastError());
	}
	
	/**
	 * updates a model via where clause
	 * @param string $model
	 * @param stdClass|array $objectquery
	 * @param string $where
	 * @throws Exception
	 */
	public function updateAll($model, $object, $where){
		$where = "$where";
		$query = $this->getUpdateQuery($model, $object, $where);
		$result = $this->query($query);
		return new ResultObject($result, $query, $this->getLastError());
	}
	/**
	 *
	 * deletes entry with id $item_id in model $model
	 * @param string $model
	 * @param integer $item_id
	 * @return boolean
	 */
	public function delete($model, $item_id){
		$where = "`id` = $item_id";
		$query = $this->getDeleteQuery($model, $where);
		$result = $this->query($query);
		return new ResultObject($result, $query, $this->getLastError());
	}

	/**
	 *
	 * deletes entry with matching where text
	 * @param string $model
	 * @param string $where
	 * @return boolean
	 */
	public function deleteAll($model, $where){
		$query = $this->getDeleteQuery($model, $where);
		$result = $this->query($query);
		return new ResultObject($result, $query, $this->getLastError());
	}

	/**
	 *
	 * gets the latest id insertet by db-connector
	 * @return int
	 */
	public function getLastInsertId(){
		return $this->connection->insert_id;
	}

	/**
	 * returns the last occured error
	 * @return string
	 */
	public function getLastError(){
		return $this->connection->error;
	}
	
	/**
	 * returns the last built query
	 * @return string
	 */
	public function getLastQuery(){
		return $this->lastQuery;
	}

	public function clearTable($table_name){
		$this->query('truncate '.$table_name);
	}
}