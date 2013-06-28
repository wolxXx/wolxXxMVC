<?
/**
 * @codeCoverageIgnore
 */
class UpdateQueryBuilderTest extends  PHPUnit_Framework_TestCase{
	public function testUpdateQueryBuilder(){
		$update = new UpdateQueryBuilder('user', array('name' => 'linus'), 1337);
		$this->assertSame("UPDATE `user` SET `name` = 'linus' WHERE `user`.id = 1337;", $update->getQueryString()->getQueryString(true));
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testUpdateQueryBuilderForThrowingExceptionHavingNoId(){
		$update = new UpdateQueryBuilder('user', array('name' => 'linus'));
		$this->assertSame("  \n  UPDATE\n\t`user`\nSET\n\t`name` = 'linus'\nWHERE\n\t`user`.id = 1337\n;", $update->getQueryString()->getQueryString(true));
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testUpdateQueryBuilderForThrowingExceptionHavingNoData(){
		$update = new UpdateQueryBuilder('user', null, 1337);
		$this->assertSame("\nUPDATE\n\t`user`\nSET\n\t`name` = 'linus'\nWHERE\n\t`user`.id = 1337\n;", $update->getQueryString()->getQueryString(true));
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testUpdateQueryBuilderForThrowingExceptionHavingEmptyData(){
		$update = new UpdateQueryBuilder('user', array(), 1337);
		$this->assertSame("\nUPDATE\n\t`user`\nSET\n\t`name` = 'linus'\nWHERE\n\t`user`.id = 1337\n;", $update->getQuery());
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testUpdateQueryBuilderForThrowingExceptionHavingNoTable(){
		$update = new UpdateQueryBuilder(null, array(1 => 2), 1337);
		$this->assertSame("\nUPDATE\n\t`user`\nSET\n\t`name` = 'linus'\nWHERE\n\t`user`.id = 1337\n;", $update->getQuery());
	}

	/**
	 * @expectedException QueryGeneratorException
	 */
	public function testUpdateQueryBuilderForThrowingExceptionHavingEmptyTable(){
		$update = new UpdateQueryBuilder('', array(1 => 2), 1337);
		$this->assertSame("\nUPDATE\n\t`user`\nSET\n\t`name` = 'linus'\nWHERE\n\t`user`.id = 1337\n;", $update->getQuery());
	}

	public function testUpdateQueryBuilderGetQueryString(){
		$update = new UpdateQueryBuilder('user', array('name' => 'linus'), 1337);
		$this->assertSame("UPDATE `user` SET `name` = 'linus' WHERE `user`.id = 1337;", $update->getQueryString()->getQueryString(true));
	}
}