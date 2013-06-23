<?
/**
 * list of fetched results
 * 
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class ResultList{
	/**
	 * the list
	 * @var array
	 */
	protected $results;
	
	/**
	 * constructor
	 * @param array $results
	 */
	public function __construct($results = array()){
		$this->results = $results;
	}
	
	/**
	 * clears all results
	 */
	protected function clearResults(){
		$this->results = array();
	}
	
	/**
	 * fetches objects from a query result
	 * @param ResultObject $result
	 * @return ResultList
	 */
	public function injectResultsViaQueryResult($result){
		$this->clearResults();
		if(false !== $result && 0 !== $result->num_rows){
			while($obj = $result->fetch_object('Result')){
				$this->results[] = $obj;
			}
			$result->close();
		}
		return $this;
	}
	
	/**
	 * setter for results
	 * @param array $results
	 */
	public function setResults($results){
		$this->results = $results;
	}
	
	/**
	 * returns all results
	 * @return array
	 */
	public function getResults(){
		return $this->results;
	}
}