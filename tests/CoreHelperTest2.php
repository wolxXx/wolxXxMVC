<?
/**
 * @codeCoverageIgnore
 */
class CoreHelperTest2 extends  PHPUnit_Framework_TestCase{
	public function testLogDeprecated(){
		$array = array('foo', array('foo'));
		$array = Helper::array_decorate($array, '_');
		$this->assertSame('_foo_', $array[0]);
		$this->assertSame('_foo_', $array[1][0]);
	}
}