<?
/**
 * @codeCoverageIgnore
 */
class QueryBuilderTest extends  PHPUnit_Framework_TestCase{
	public function testGenerateRELOR(){
		$conditions = array(
			'from' => 'foo',
			'where' => array(
				'RELOR' => array(
					'ROFL' => 'LOL'
				),
				'ORLIKE' => array(
					'foo' => 'bar',
					'bar' => 'foo'
				)
			)
		);
		$builder = new SelectQueryBuilder();
		$builder->setConditions($conditions);
		$builder->getQueryString();
	}

	public function testGenerateRELOR2(){
		$conditions = array(
			'from' => 'foo',
			'where' => array(
				'ORLIKE' => array(
					'foo' => 'bar',
					'bar' => 'foo'
				),
				'RELOR' => array(
					'ROFL' => 'LOL',
					'ROFL2' => '2LOL'
				)
			)
		);
		$builder = new SelectQueryBuilder();
		$builder->setConditions($conditions);
		$builder->getQueryString();
	}

	public function testGenerateOrLike(){
		$conditions = array(
			'from' => 'foo',
			'where' => array(
				'OR' => array(
					'ROFL' => 'LOL'
				),
				'ORLIKE' => array(
					'foo' => 'bar',
					'bar' => 'foo'
				)
			)
		);
		$builder = new SelectQueryBuilder();
		$builder->setConditions($conditions);
		$builder->getQueryString();
	}

	public function testGenerateOr(){
		$conditions = array(
			'from' => 'foo',
			'where' => array(
				'OR' => array(
					'foo' => 'bar',
					'bar' => 'foo'
				)
			)
		);
		$builder = new SelectQueryBuilder();
		$builder->setConditions($conditions);
		$builder->getQueryString();
	}
	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testGenerateWhere(){
		$find = array(
			'from' => '',
			'where' => array()
		);
		$builder = new SelectQueryBuilder();
		$builder->setConditions($find);
		$builder->getQueryString();
	}

	public function testClearQueryString(){
		$queryStringObject = new QueryString('abc def  ghi');
		$query = $queryStringObject->getQueryString(false);
		$this->assertTrue(strstr($query, '  ') !== false);
		$query = $queryStringObject->getQueryString(true);
		$this->assertTrue(strstr($query, '  ') === false);
	}

	public function testSetConnection(){
		$select = new SelectQueryBuilder();
		$select->setConnection(null);
		$this->assertNull($select->getConnection());
	}

	public function testMapSignsToString(){
		$select = new SelectQueryBuilder();
		$this->assertSame('LESS', $select->mapSignsToString('<'));
		$this->assertSame('SAMEORLESS', $select->mapSignsToString('<='));
		$this->assertSame('MORE', $select->mapSignsToString('>'));
		$this->assertSame('SAMEORMORE', $select->mapSignsToString('>='));
		$this->assertSame('PEWPEW', $select->mapSignsToString('PEWPEW'));
	}
}
