<?

require_once __DIR__.'/../Stack.php';
require_once __DIR__.'/../CoreHelper.php';

if(false === class_exists('Helper')){
	class Helper extends CoreHelper{}
}
/**
 * @codeCoverageIgnore
 */
class StackTest extends  PHPUnit_Framework_TestCase{
	public function testClearStack(){
		session_id('foobar!');
		Stack::getClearInstance()->set('foo', 'bar');
	}

	public function testUsual(){
		Stack::getInstance()->set('a', 'b');
		$this->assertEquals(Stack::getInstance()->get('a'), 'b');
		$this->assertSame(Stack::getInstance()->get('a'), 'b');
		$this->assertSame(Stack::getInstance()->get('a'), "b");

		Stack::getInstance()->clear();
		Stack::getInstance()->set('blubb', 'yeah');
		Stack::getInstance()->get('blubb');
		$this->assertNotContains('blubb not found in stack', Stack::getInstance()->getMessages());
	}

	public function testClearInstance(){
		$this->assertNull(Stack::getClearInstance()->get('def', null));
	}

	public function testNull(){
		Stack::getInstance()->clear();
		$this->assertEquals(Stack::getInstance()->get('def'), null);
	}

	public function testMessages(){
		Stack::getInstance()->clear();
		$this->assertEmpty(Stack::getInstance()->getMessages());
		Stack::getInstance()->get('hamwanet');
		$this->assertContains('hamwanet not found in stack. using default: ', Stack::getInstance()->getMessages());
	}

	public function testClear(){
		Stack::getInstance()->set('a', 'blubb');
		Stack::getInstance()->clear();
		$this->assertEquals(Stack::getInstance()->get('a'), null);
	}

	public function testGetAll(){
		Stack::getInstance()->clear();
		Stack::getInstance()->set('a', 'b');
		Stack::getInstance()->set('b', 'a');
		$this->assertEquals(Stack::getInstance()->getAll(), array('a' => 'b', 'b' => 'a'));
		$this->assertContains('a', Stack::getInstance()->getAll());
		$this->assertContains('b', Stack::getInstance()->getAll());
	}

	public function testUnset(){
		Stack::getInstance()->set('a', 'blubb');
		Stack::getInstance()->unsetKey('a');
		$this->assertEquals(Stack::getInstance()->get('a'), null);
	}

	public function testDebug(){
		Stack::getInstance()->clear();
		Stack::getInstance()->set('a', 'b');
		$this->expectOutputString("");
		Stack::getInstance()->debug();
	}

}