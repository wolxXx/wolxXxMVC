<?
/**
 * @codeCoverageIgnore
 */
class SelectQueryBuilderTest extends  PHPUnit_Framework_TestCase{
	public function testSelectQueryBuilder(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user'
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
	}

	public function testSelectQueryBuilderWithLimitWithOneEntry(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => 1
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1/');
	}

	public function testSelectQueryBuilderWithLimitWithOneEntryAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => array(1)
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1/');
	}

	public function testSelectQueryBuilderWithLimitFromMethodIsOne(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'method' => 'one'
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1/');
	}

	public function testSelectQueryBuilderWithLimitWithTwoEntriesAsArray(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'limit' => array(1,2)
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1, 2/');
	}

	public function testSelectQueryBuilderWithOrder(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'order' => 'pewpew'
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/ORDER BY pewpew/');
	}

	public function testSelectQueryBuilderWithGroup(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'from' => 'user',
			'group' => 'pewpew'
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/GROUP BY pewpew/');
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
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1,2/');
		$this->expectOutputRegex('/id, nick/');
	}

	public function testSelectQueryBuilderWithFieldsAsNull(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'fields' => null,
			'from' => 'user',
			'limit' => array(1,2)
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1,2/');
		$this->expectOutputRegex('/\*/');
	}

	public function testSelectQueryBuilderWithDistinct(){
		$select = new SelectQueryBuilder();
		$select->setConditions(array(
			'distinct' => true,
			'fields' => null,
			'from' => 'user',
			'limit' => array(1,2)
		));
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/SELECT DISTINCT/');
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/LIMIT 1,2/');
		$this->expectOutputRegex('/\*/');
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
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/SELECT DISTINCT/');
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/WHERE (/');
		$this->expectOutputRegex('/pewpew/');
		$this->expectOutputRegex('/LIKE \'%tools%\'/');
		$this->expectOutputRegex('/LIKE \'%asdf%\'/');
		$this->expectOutputRegex('/\*/');
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
		#$this->assertSame("SELECT DISTINCT\n\t\tpewpew, mooo\n\tFROM\n\t\tuser u, blog b\n\tWHERE\n\t\tb.id = '1234'
		#AND asd = 'Ad'  AND u.id < '1234'  AND b.pew <= '1337' AND u.id > '1234'  AND b.pew >= '1337' AND hgf IS NULL
		#AND hgfhgfd IS NULL  AND asf LIKE '%141%'  AND 0 LIKE '%asfoas%'  AND 1 LIKE '%uohasd%' AND hgfd IS NOT NULL
		#AND as.df = jk.lo AND u.id != 'b.id' AND b.moo NOT IN ('milk', 'cheese') AND b IN ('1', '2', '3', '4', '5')
		#AND c IN ('5', '6', '7', '8', '9', '10') AND (b.asd = 'Adf' OR b.asda = 'kiik')\n\tGROUP BY gasf\n\t
		#ORDER BY ascdf\n\tLIMIT 1, 10;", $select->getQueryString()->getQueryString());
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/SELECT DISTINCT/');
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/WHERE (/');
		$this->expectOutputRegex('/pewpew, moo/');
		$this->expectOutputRegex('/user u/');
		$this->expectOutputRegex('/blog b/');
		$this->expectOutputRegex('/b.id = \'1234\'/');
		$this->expectOutputRegex('/asd = \'Ad\'/');
		$this->expectOutputRegex('/u.id > \'1234\'/');

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
		#$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/SELECT/');
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/WHERE (/');
		$this->expectOutputRegex('/pewpew, moo/');
		$this->expectOutputRegex('/user/');
		$this->expectOutputRegex('/\*/');
		$this->expectOutputRegex('/LIMIT 1, 2/');
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
		#$this->assertSame("SELECT \n\t\t*\n\tFROM\n\t\tuser u, blog b\n\tWHERE\n\t\t1 = 1\n\t\n\t\n\tLIMIT 1, 2;", $select->getQueryString()->getQueryString());
		echo $select->getQueryString()->getQueryString();
		$this->expectOutputRegex('/SELECT/');
		$this->expectOutputRegex('/FROM/');
		$this->expectOutputRegex('/WHERE (/');
		$this->expectOutputRegex('/blog b/');
		$this->expectOutputRegex('/user u/');
		$this->expectOutputRegex('/\*/');
		$this->expectOutputRegex('/LIMIT 1, 2/');

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