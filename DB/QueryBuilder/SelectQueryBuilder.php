<?
/**
 * select query builder
 * wrappes a condition array to a select query string
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage QueryBuilder
 * @version 1.2
 */
class SelectQueryBuilder extends QueryBuilder{
	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(true === empty($this->conditions)){
			throw new QueryGeneratorException('empty conditions');
		}

		if(false === isset($this->conditions['from'])){
			throw new QueryGeneratorException();
		}
	}

	/**
	 * checks if the query was a query for selecting more than one element
	 * @return boolean
	 */
	public function isQueryForAll(){
		return 'all' === $this->conditions['method'];
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$this->mergeConditions($this->conditions);
		$distinct = $this->generateDistinct();
		$fields = $this->generateFields();
		$from = $this->generateFrom();
		$where = $this->generateWhere();
		$group = $this->generateGroup();
		$order = $this->generateOrder();
		$limit = $this->generateLimit();
		$query =
<<<SQL
	SELECT {$distinct}
		{$fields}
	FROM
		{$from}
	WHERE
		{$where}
	{$group}
	{$order}
	{$limit}
SQL;
		#$query = "SELECT $distinct\n\t\t$fields\n\tFROM\n\t\t$from\n\tWHERE\n\t\t$where\n\t$group\n\t$order\n\t$limit;";
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		$query = $this->generateQuery();
		$queryString = new QueryString();
		$queryString->setQueryString($query);
		return $queryString;
	}
}