<?
/**
 * object for finding items
 * implented as singleton
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 2.0
 */
abstract class CoreModel{
	/**
	 * an instance of the databaseManager
	 * @var DatabaseManager
	 */
	protected $databaseManager;

	/**
	 * all available models
	 * they are called if the requested function does not exist
	 * @var array
	 */
	private $availableModels = array();

	/**
	 * tries to call a moved or refactored model function
	 * @param string $function
	 * @param array $params
	 * @throws Exception
	 */
	public function __call($function, $params){
		foreach($this->availableModels as $current){
			if(true === method_exists($current, $function)){
				return call_user_func_array(array($current, $function), $params);
			}
		}
		die('WTF?!');
	}

	/**
	 * constructor
	 * @param DatabaseManager $manager
	 */
	public function __construct(DatabaseManager $manager = null){
		if(null === $manager){
			$manager = DatabaseManager::getInstance();
		}
		$this->databaseManager = $manager;
		if(true === in_array(get_called_class(), array('Model', 'CoreModel'))){
			return;
		}
		foreach(Helper::scanDirectory(Helper::getDocRoot().'application'.DIRECTORY_SEPARATOR.'models', null, null, true) as $current){
			$className = str_replace(Helper::getFileExtension($current, true), '', Helper::getFileName($current));
			$this->availableModels[] = new $className();
		}
	}

	/**
	 *
	 * @param QueryString $queryStringObject
	 */
	protected function query($queryStringObject){
		$result = $this->databaseManager->find($queryStringObject);
		return new QueryResultObject($result, $queryStringObject->getQueryString(), $result->getError());
	}

	/**
	 * finds one item by a query string object
	 * @param QueryStringInterface $queryString
	 * @return Result | null
	 */
	public function findOneByQueryString(QueryStringInterface $queryString){
		$result = $this->query($queryString);
		$resultList = new ResultList();
		$resultList->injectResultsViaQueryResult($result->getResult()->getResult());
		$results = $resultList->getResults();
		if(true === empty($results)){
			return null;
		}
		return $results[0];
	}

	/**
	 * finds all occurences by a query string
	 * @param QueryStringInterface $queryString
	 * @return array
	 */
	public function findAllByQueryString(QueryStringInterface $queryString){
		$result = $this->query($queryString);
		$resultList = new ResultList();
		$resultList->injectResultsViaQueryResult($result->getResult()->getResult());
		$results = $resultList->getResults();
		return $results;
	}

	/**
	 * finder
	 * @param array $conditions
	 * @return null | Result | array
	 */
	public function find($conditions){
		$queryBuilder = new SelectQueryBuilder();
		$queryBuilder->setConditions($conditions);
		if(true === $queryBuilder->isQueryForAll()){
			return $this->findAllByQueryString($queryBuilder->getQueryString());
		}
		return $this->findOneByQueryString($queryBuilder->getQueryString());
	}

	/**
	 * shortcut for just finding one item for a table
	 * if no $field is provided, it takes the id-field
	 * @param string $model
	 * @param string | integer $key
	 * @param string $field
	 * @return Result | null
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
	 * returns the amount of found elems
	 * @param array $conditions
	 */
	public function count($conditions){
		$conditions['fields'] = array('COUNT(*) as count');
		$conditions['method'] = 'one';
		$result = $this->find($conditions);
		return $result->count;
	}
}