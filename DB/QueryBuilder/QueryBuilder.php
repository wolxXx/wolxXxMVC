<?
/**
 * abstract query builder
 * needs a connection. you can set it via dic or dis.
 * needs conditions as array. you can set it via dic or dis.
 * provides functions for transforming a condition array to a query string
 *
 * @author wolxXx
 * @version 1.2
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 */
abstract class QueryBuilder{
	/**
	 * connection to the database
	 *
	 * @var mysqli
	 */
	protected $connection;

	/**
	 * array of conditions
	 * @var array
	 */
	protected $conditions;

	/**
	 * constructor. you can use dependency injection via constructor (dic)
	 * returns this in pattern of fluent interface
	 *
	 * @param array | null $conditions
	 * @param mysqli | null $connection
	 * @return QueryBuilder
	 */
	public function __construct($conditions = null, $connection = null){
		$this->conditions = $conditions;
		$this->connection = $connection;
		if(null === $this->connection){
			$this->connection = DatabaseManager::getInstance()->getConnection();
		}
		return $this;
	}

	/**
	 * checks if the set conditions fulfill the needed minimal expectations
	 *
	 * @throws Exception
	 * @throws QueryGeneratorException
	 */
	abstract protected function checkConditions();

	/**
	 * generates the query from the set conditions
	 *
	 * @return string
	 * @throws Exception
	 * @throws QueryGeneratorException
	*/
	abstract public function generateQuery();

	/**
	 * returns an instance of a QueryString
	 *
	 * @return QueryString
	 */
	abstract public function getQueryString();

	/**
	 * setter for the connection
	 * returns this in pattern of fluent interface
	 *
	 * @param mysqli $connection
	 * @return QueryBuilder
	 */
	public function setConnection($connection){
		$this->connection = $connection;
		return $this;
	}

	/**
	 * getter for the connection
	 *
	 * @return mysqli
	 */
	public function getConnection(){
		return $this->connection;
	}

	/**
	 * setter for conditions
	 * returns this in pattern of fluent interface
	 *
	 * @param array $conditions
	 * @return QueryBuilder
	 */
	public function setConditions($conditions){
		$this->conditions = $conditions;
		$this->mergeConditions($conditions);
		return $this;
	}

	/**
	 * merges the default conditions with the provided
	 * it ensures that the minimal requirements are set
	 *
	 * @param array $conditions
	 */
	protected function mergeConditions($conditions){
		$myconditions = array(
			'method' => 'all', #all | one -> returns array or object
			'from' => null, #table names or join clauses. the only field that is required!!
			'limit' => null, # integer, null, array{1}, array{2} -> int => limit i, null => no limit, array{1} => limit array[0], array{2} => limit array[0], array[1]
			'fields' => '*', # string, array:  string = explicit this field, array => elements will be imploded to string, can contain as clauses
			'where' => null, # array only! used for prepared statements
			'order' => null, # string
			'group' => null, # string
			'distinct' => false, #boolean
		);
		//overwrite my defaults with your conditions
		$this->conditions = array_merge($myconditions, $conditions);
	}

	/**
	 * converts signs as < or > to string like LESS or MORE
	 *
	 * @param string $sign
	 * @return string
	 */
	public function mapSignsToString($sign){
		switch($sign){
			case '<':{
				return 'LESS';
			}break;
			case '<=':{
				return 'SAMEORLESS';
			}break;
			case '>':{
				return 'MORE';
			}break;
			case '>=':{
				return 'SAMEORMORE';
			}break;
			default:{
				return $sign;
			}break;
		}

	}


	/**
	 * generates the limit string
	 * you can provide null, an array or a string or an integer
	 *
	 * @return string
	 */
	protected function generateLimit(){
		if(null === $this->conditions['limit']){
			$limit = '';
			if('one' === $this->conditions['method']){
				$limit = 'LIMIT 1';
			}
		}else{
			$limit = 'LIMIT ';
			if(false === is_array($this->conditions['limit'])){
				$limit .= $this->conditions['limit'];
			}else{
				$limit .= $this->conditions['limit'][0];
				if(true === isset($this->conditions['limit'][1])){
					$limit .= ', '.$this->conditions['limit'][1];
				}
			}
		}
		return $limit;
	}

	/**
	 * fields may be null, then select *, but fields may be a string,
	 * then select that, otherwise it should be an array, then implode to string
	 *
	 * @return string
	 */
	protected function generateFields(){
		if(null === $this->conditions['fields'] || empty($this->conditions['fields'])){
			$fields = '*';
		}else{
			if(true === is_array($this->conditions['fields'])){
				$fields = implode(', ', $this->conditions['fields']);
			}else{
				$fields = $this->conditions['fields'];
			}
		}
		return $fields;
	}

	/**
	 * insert an order statement. comes as string from conditions, just concat
	 *
	 * @return string
	 */
	protected function generateOrder(){
		$order = '';
		if(null !== $this->conditions['order']){
			$order = 'ORDER BY '.$this->conditions['order'];
		}
		return $order;
	}

	/**
	 * insert a group by statement. comes as string from conditions, just concat
	 *
	 * @return string
	 */
	protected function generateGroup(){
		$group = '';
		if(null !== $this->conditions['group']){
			$group = 'GROUP BY '.$this->conditions['group'];
		}
		return $group;
	}

	/**
	 * if boolean field distinct is set to true, insert distinct statement
	 *
	 * @return string
	 */
	protected function generateDistinct(){
		$distinct = '';
		if(false !== $this->conditions['distinct']){
			$distinct = 'DISTINCT';
		}
		return $distinct;
	}

	/**
	 * param can be array or string
	 *
	 * @throws QueryGeneratorException
	 * @return string
	 */
	protected function generateFrom(){
		if(null === $this->conditions['from']){
			throw new QueryGeneratorException('no from selected');
		}
		$from = $this->conditions['from'];
		if(true === is_array($this->conditions['from'])){
			if(true === empty($this->conditions['from'])){
				throw new QueryGeneratorException('from is array and empty');
			}
			$from = implode(', ', $this->conditions['from']);
		}
		return $from;
	}

	/**
	 * creates the query part for a simple comparison
	 *
	 * @param string $where
	 * @param string $left
	 * @param string | number $right
	 * @return string
	 */
	protected function generateWhereSimple($where, $left, $right){
		if('' === $where){
			$and = '';
		}else{
			$and = ' AND ';
		}
		$where .= "$and$left = '$right'";
		return trim($where);
	}

	/**
	 * creates the query part for the or query
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereOR($where, $right){
		if('' === $where){
			$where .= '(';
		}else{
			$where .= ' AND (';
		}
		$blank = true;
		foreach($right as $key => $value){
			if(true !== $blank){
				$where .= ' OR ';
			}
			$where .= "$key = '$value'";
			$blank = false;
		}
		$where .= ')';
		return trim($where);
	}

	/**
	 * creates the query part for the or query where param is LIKEed
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereORLIKE($where, $right){
		if('' === $where){
			$where .= '(';
		}else{
			$where .= ' AND (';
		}
		$blank = true;
		foreach($right as $key => $value){
			if(true !== $blank){
				$where .= ' OR ';
			}
			$where .= "`$key` LIKE '%$value%'";
			$blank = false;
		}
		$where .= ')';
		return trim($where);
	}

	/**
	 * creates the query part for a relational or comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereRELOR($where, $right){
		if('' === $where){
			$where .= '(';
		}else{
			$where .= ' AND (';
		}
		$blank = true;
		foreach($right as $key => $value){
			if(!$blank){
				$where .= ' OR ';
			}
			$where .= "$key = $value";
			$blank = false;
		}
		$where .= ')';
		return trim($where);
	}

	/**
	 * creates the query part for a > compparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereMORE($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= "$and $name > '$value'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a >= comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereSAMEORMORE($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= " $and $name >= '$value'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a < comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereLESS($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= " $and $name < '$value'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a <= comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereSAMEORLESS($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= " $and $name <= '$value'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a like comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereLIKE($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= " $and $name LIKE '%$value%'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for null query
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereNULL($where, $right){
		foreach($right as $name){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= "$and $name IS NULL";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a not null query
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereNOTNULL($where, $right){
		foreach($right as $name){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= "$and $name IS NOT NULL";
		}
		return trim($where);
	}

	/**
	 * creates the query part for a negotiated comparison
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereNOT($where, $right){
		foreach($right as $name => $value){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= "$and $name != '$value'";
		}
		return trim($where);
	}

	/**
	 * creates the query part for in query
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereIN($where, $right){
		foreach($right as $name => $values){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$values = array_unique($values);
			$where .= "$and $name IN ('".implode('\', \'', $values).'\')';
		}
		return trim($where);
	}

	/**
	 * creates the query part for not in query
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereNOTIN($where, $right){
		foreach($right as $name => $values){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$values = array_unique($values);
			$where .= "$and $name NOT IN ('".implode('\', \'', $values).'\')';
		}
		return trim($where);
	}
	/**
	 * creates the query part for relational comparisons
	 *
	 * @param string $where
	 * @param array $right
	 * @return string
	 */
	protected function generateWhereREL($where, $right){
		foreach($right as $name => $values){
			$and = '';
			if('' !== $where){
				$and = ' AND';
			}
			$where .= "$and $name = $values";
		}
		return trim($where);
	}

	/**
	 * this is the most interesting section..
	 * it runs through the where section of the search
	 * conditions an grabs the provided information
	 *
	 * @throws QueryGeneratorException
	 * @return string
	 */
	protected function generateWhere(){
		if(false === isset($this->conditions['where']) || null === $this->conditions['where'] || empty($this->conditions['where'])){
			return '1 = 1';
		}
		$where = '';
		foreach($this->conditions['where'] as $left => $right){
			if(false === method_exists($this, 'generateWhere'.$this->mapSignsToString($left))){
				$where = $this->generateWhereSimple($where, $left, $right);
				continue;
			}
			if(false === is_array($right)){
				throw new QueryGeneratorException('right ('.$right.') should be an array for left ('.$left.')!');
			}
			$where = $this->{'generateWhere'.$this->mapSignsToString($left)}($where, $right);
		}
		return trim($where);
	}
}