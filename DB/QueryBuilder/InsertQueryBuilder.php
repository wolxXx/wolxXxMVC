<?
/**
 * creates an insert into $table query string
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class InsertQueryBuilder extends QueryBuilder{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the data to be saved
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param array $data
	 */
	public function __construct($table, $data){
		$this->table = $table;
		$this->data = $data;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(false === is_array($this->data)){
			throw new QueryGeneratorException('could not create model if data is not an array');
		}

		if(true === empty($this->data)){
			throw new QueryGeneratorException('recieved no data');
		}
	}

	/**
	 * runs through the data array and takes the keys for the keys
	 * section and the values for the value section.
	 * this might be a clause for cptn. obvious
	 *
	 * as long as we have no multitype returns as in 5.5 it returns an array
	 *
	 * @return array
	 */
	private function generateStringFromArray(){
		$keys = '';
		$values = '';
		foreach(array_keys($this->data) as $key){
			$keys .= '`'.$key.'`,';
			$values .= "'".DatabaseManager::getInstance()->getConnection()->escape($this->data[$key])."',";
		}
		$keys = rtrim($keys, ',');
		$values = rtrim($values, ',');
		return array('keys' => $keys, 'values' => $values);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$generatedArray = $this->generateStringFromArray();
		$keys = $generatedArray['keys'];
		$values = $generatedArray['values'];
		$query = "INSERT INTO `".$this->table."` ($keys) VALUES ($values);";
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		$queyString = new QueryString();
		$queyString->setQueryString($this->generateQuery());
		return $queyString;
	}
}