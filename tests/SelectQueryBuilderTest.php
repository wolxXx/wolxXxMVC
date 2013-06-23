<?
class SelectQueryBuilderTest extends  PHPUnit_Framework_TestCase{
	public function testSelectQueryBuilder(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user'
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\t;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithLimitWithOneEntry(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => 1
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithLimitWithOneEntryAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => array(1)
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithLimitFromMethodIsOne(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'method' => 'one'
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithLimitWithTwoEntriesAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithOrder(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'order' => 'pewpew'
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\tORDER BY pewpew\n\t;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithGroup(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'group' => 'pewpew'
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\tGROUP BY pewpew\n\t\n\t;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithFieldsAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'fields' => array(
				'id', 'nick'
			),
			'from' => 'user',
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT \n\t\tid, nick\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithFieldsAsNull(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'fields' => null,
			'from' => 'user',
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithDistinct(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'distinct' => true,
			'fields' => null,
			'from' => 'user',
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT DISTINCT\n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithoutFrom(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => null,
		));
		$select->getQueryString();
		$this->assertTrue(false);
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithoutWhereAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user u',
			'where' => array(
				'OR' => 'Asdf',
				'RELOR' => 'asdf',
				'<=' => 'asdf'
			)
		));
		$select->getQueryString();
		$this->assertTrue(false);
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithoutWhereAsNotArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => array(
				'pewpew'
			),
			'where' => array(
				'OR' => 'Asdf',
				'RELOR' => 'asdf',
				'<=' => 'asdf'
			)
		));
		$select->getQueryString();
		$this->assertTrue(false);
	}

	public function testSelectQueryBuilderORLIKE(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => array(
				'pewpew'
			),
			'where' => array(
				'ORLIKE' => array(
					'pewpew' => 'asdf',
					'moo' => 'tools',
				)
			)
		));
		$this->assertSame("SELECT * FROM pewpew WHERE (`pewpew` LIKE '%asdf%' OR `moo` LIKE '%tools%');", $select->getQueryString()->getQueryString(true));
	}

	public function testWhere(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'distinct' => true,
			'from' => array(
				'user u', 'blog b'
			),
			'limit' => array(
				1,10
			),
			'order' => 'ascdf',
			'group' => 'gasf',
			'fields' => array(
				'pewpew', 'mooo'
			),
			'where' => array(
				'b.id' => '1234',
				'asd' => 'Ad',
				'<' => array(
					'u.id' => '1234'
				),
				'<=' => array(
					'b.pew' => 1337,
				),
				'>' => array(
					'u.id' => '1234'
				),
				'>=' => array(
					'b.pew' => 1337,
				),
				'NULL' => array(
					'hgf', 'hgfhgfd'
				),
				'LIKE' => array(
					'asf' => 141,
					'asfoas', 'uohasd'
				),
				'NOTNULL' => array(
					'hgfd'
				),
				'REL' => array(
					'as.df' => 'jk.lo'
				),
				'NOT' => array(
					'u.id' => 'b.id'
				),
				'NOTIN' => array(
					'b.moo' => array('milk', 'cheese')
				),
				'IN' => array(
					'b' => range(1,5),
					'c' => range(5,10)
				),
				'OR' => array(
					'b.asd' => 'Adf',
					'b.asda' => 'kiik'
				)
			)
		));
		$this->assertSame("SELECT DISTINCT\n\t\tpewpew, mooo\n\tFROM\n\t\tuser u, blog b\n\tWHERE\n\t\tb.id = '1234' AND asd = 'Ad'  AND u.id < '1234'  AND b.pew <= '1337' AND u.id > '1234'  AND b.pew >= '1337' AND hgf IS NULL AND hgfhgfd IS NULL  AND asf LIKE '%141%'  AND 0 LIKE '%asfoas%'  AND 1 LIKE '%uohasd%' AND hgfd IS NOT NULL AND as.df = jk.lo AND u.id != 'b.id' AND b.moo NOT IN ('milk', 'cheese') AND b IN ('1', '2', '3', '4', '5') AND c IN ('5', '6', '7', '8', '9', '10') AND (b.asd = 'Adf' OR b.asda = 'kiik')\n\tGROUP BY gasf\n\tORDER BY ascdf\n\tLIMIT 1, 10;", $select->getQueryString()->getQueryString());
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithEmptyFrom(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'distinct' => true,
			'fields' => null,
			'from' => array(),
			'limit' => array(1,2)
		));
		$select->getQueryString();
		$this->assertTrue(false);
	}

	public function testSelectQueryBuilderWithFieldsAsEmpty(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'fields' => array(),
			'from' => 'user',
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderWithFieldsAsFilledArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'fields' => array(),
			'from' => array(
				'user u', 'blog b'
			),
			'limit' => array(1,2)
		));
		$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser u, blog b\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
	}

	public function testSelectQueryBuilderIsQueryForAll(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user'
		));
		$this->assertTrue($select->isQueryForAll());
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithoudConditions(){
		$select = new SelectQueryBuilder();
		$select->getQueryString();
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testSelectQueryBuilderWithoudFromCondition(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'where' => 'user'
		));
		$select->getQueryString();
	}
}