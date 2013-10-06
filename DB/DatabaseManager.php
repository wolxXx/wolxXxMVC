<?
/**
 * accepts QueryString objects and delegates them to the database connection
 * implemented as singleton pattern
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class DatabaseManager{
	/**
	 * instance of itself -> singleton pattern
	 *
	 * @var DatabaseManager
	 */
	private static $instance;

	/**
	 * instance of a Connection
	 *
	 * @var Connection
	 */
	protected $connection;

	/**
	 * if the current query shall be logged
	 *
	 * @var boolean
	 */
	protected $forceLogging = false;

	/**
	 * singleton accessor
	 *
	 * @return DatabaseManager
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * constructor
	 *
	 * sets up a new Connection instance
	 */
	private function __construct(){
		$this->connection = new Connection();
		$this->connection
			->setHost(Stack::getInstance()->get('db_host'))
			->setUser(Stack::getInstance()->get('db_user'))
			->setPassword(Stack::getInstance()->get('db_pass'))
			->setDatabase(Stack::getInstance()->get('db_table'))
			->connect();
	}

	/**
	 * enables or disables forced logging
	 *
	 * @param boolean $force
	 * @return DatabaseManager
	 */
	public function setForceLogging($force = true){
		$this->forceLogging = $force;
		return $this;
	}

	/**
	 * queries a query. sounds good, eh
	 * takes the execution time
	 * calls the logger
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return QueryResultObject
	 */
	public function query(QueryStringInterface $queryStringObject){
		$start = microtime(true);
		$result = $this->connection->query($queryStringObject->getQueryString());
		$end = microtime(true);
		$resultObject = new QueryResultObject($result, $queryStringObject->getQueryString(), $this->connection->getError());
		try{
			$this->log($resultObject, $start, $end);
		}catch (Exception $exception){
			Helper::logToFile('query exception: '.$exception->getMessage(), 'queryexceptionlog');
		}
		return $resultObject;
	}

	/**
	 * logs a query
	 * as default, it only logs if there was an error while querying or the execution of the query took more than one second
	 * to enable forced logging, set DatabaseManager->setForceLogging(true)
	 *
	 * @param QueryResultObject $queryResultObject
	 * @param float $start
	 * @param float $end
	 */
	protected function log($queryResultObject, $start, $end){
		$result = $queryResultObject->getResult();
		$query = $queryResultObject->getQuery();
		$execution = $end - $start;
		$execution = sprintf("%000002.6f", $execution);
		$execution = str_pad($execution, 9, 0, STR_PAD_LEFT);
		$errorNumber = $this->connection->getErrno();
		$errorString = $this->connection->getError();
		$errorString = '' === $errorString? '-none-' : $errorString;
		$success = true === $queryResultObject->queryWasSuccessfull()? 'successfull' : 'failed';
		if(false === is_bool($result)){
			$resultsValue = $result->num_rows;
			$resultsText = "Results:\t";
		}else{
			$resultsValue = $this->connection->getAffectedRows();
			$resultsText = "Affected rows:\t";
		}
		$originalTrace = debug_backtrace(0);
		$start = 100;
		while(false === isset($originalTrace[$start])){
			$start--;
		}
		$trace = $originalTrace[$start];
		while(false === isset($trace['file'])){
			$trace = $originalTrace[--$start];
		}
		$file = str_replace(Helper::getDocRoot(), '', $trace['file']);

		$class = isset($trace['class'])? $trace['class'].' at ' : '';
		$occurenced = $class.$file.' at line '.$trace['line'];
		$date = Helper::getDate();
		$userIP = Helper::getUserIP();
		$userName = Auth::isLoggedIn()? Auth::getUserNick() : 'arno nym';
		$url = Helper::getCurrentURL();

		$logtext = PHP_EOL.'____________________________________________'.PHP_EOL;
		$logtext .= "Query string:\n\t$query".PHP_EOL;
		$logtext .= "Execution time:\t$execution s".PHP_EOL;
		$logtext .= "Error string:\t$errorString".PHP_EOL;
		$logtext .= "Error number:\t$errorNumber".PHP_EOL;
		$logtext .= "Query result:\t$success".PHP_EOL;
		$logtext .= "$resultsText$resultsValue".PHP_EOL;
		$logtext .= "Occurenced:\t$occurenced".PHP_EOL;
		$logtext .= "Date:\t\t$date".PHP_EOL;
		$logtext .= "User IP:\t$userIP".PHP_EOL;
		$logtext .= "User name:\t$userName".PHP_EOL;
		$logtext .= "current URL:\t$url".PHP_EOL;
		$logtext .= '____________________________________________'.PHP_EOL;

		if(false === $queryResultObject->queryWasSuccessfull()){
			Helper::logToFile($logtext, 'dberror');
		}
		if('1' !== Stack::getInstance()->get('disable_db_log') || true === $this->forceLogging || (int)$execution > 1){
			Helper::logToFile($logtext, 'dblog');
		}
	}

	/**
	 * saves a new item in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return QueryResultObject
	 */
	public function save(QueryStringInterface $queryStringObject){
		$result = $this->query($queryStringObject);
		$result->setLastInsertId($this->connection->getLastInsertId());
		return $result;
	}

	/**
	 * updates an item in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return QueryResultObject
	 */
	public function update(QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * deletes items in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return QueryResultObject
	 */
	public function delete(QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * finds items in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return QueryResultObject
	 */
	public function find(QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * getter for the connection
	 *
	 * @return Connection
	 */
	public function getConnection(){
		return $this->connection;
	}
}