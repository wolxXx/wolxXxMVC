<?
/**
 * @codeCoverageIgnore
 */
class QueryBuilderTest extends  PHPUnit_Framework_TestCase{
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
