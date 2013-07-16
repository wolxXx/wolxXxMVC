<?
/**
 * class for having a nicer and shorter mysql result object
 * wrapper
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.3
 */
class QueryResultObject{
	/**
	 * the error message if an error occured or null
	 *
	 * @var string | null
	 */
	protected $error;

	/**
	 * the id if the query was an insert query or null
	 *
	 * @var interger | null
	 */
	protected $lastInsertId;

	/**
	 * the result of the query
	 *
	 * @var mysqli_result
	 */
	protected $result;

	/**
	 * the made query
	 *
	 * @var string
	 */
	protected $query;

	/**
	 * constructor
	 *
	 * @param mysqli_result $result
	 * @param string $query
	 * @param string | null $error
	 * @param integer | null $lastInsertId
	 */
	public function __construct($result, $query, $error, $lastInsertId = null){
		$this
			->setResult($result)
			->setQuery($query)
			->setError($error)
			->setLastInsertId($lastInsertId)
		;
	}

	/**
	 * getter for the error message
	 *
	 * @return string
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * setter for the error message
	 *
	 * @param string | null $error
	 * @return ResultObject
	 */
	public function setError($error){
		$this->error = $error;
		return $this;
	}

	/**
	 * getter for the last insert id
	 *
	 * @return interger
	 */
	public function getLastInsertId(){
		return $this->lastInsertId;
	}

	/**
	 * setter for the last insert id
	 *
	 * @param integer | null $lastInsertId
	 * @return ResultObject
	 */
	public function setLastInsertId($lastInsertId){
		$this->lastInsertId = $lastInsertId;
		return $this;
	}

	/**
	 * getter for the result
	 *
	 * @return mysqli_result
	 */
	public function getResult(){
		return $this->result;
	}

	/**
	 * setter for the result
	 *
	 * @param mysqli_result | null $result
	 * @return ResultObject
	 */
	public function setResult($result){
		$this->result = $result;
		return $this;
	}

	/**
	 * getter for the query
	 *
	 * @return string
	 */
	public function getQuery(){
		return $this->query;
	}

	/**
	 * setter for the query
	 *
	 * @param string $query
	 * @return ResultObject
	 */
	public function setQuery($query){
		$this->query = $query;
		return $this;
	}

	/**
	 * determines if the query was successfull
	 *
	 * @return boolean
	 */
	public function queryWasSuccessfull(){
		return '' === $this->error;
	}
}