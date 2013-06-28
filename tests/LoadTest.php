<?
/**
 * @codeCoverageIgnore
 */
require_once __DIR__.'/../Stack.php';
require_once __DIR__.'/../Load.php';
require_once __DIR__.'/../CoreHelper.php';
if(false === class_exists('Helper')){
	class Helper extends CoreHelper{}
}
Stack::getInstance()->set('controller', 'cms');

/**
 * @codeCoverageIgnore
 */
class LoadTest extends  PHPUnit_Framework_TestCase{
	public function testPartialWithDirectVars(){
		Load::getInstance()->setLayout('../Lib/wolxXxMVC/tests/_src/');
		$this->expectOutputString('foo');
		Load::getInstance()->partial('testview', array('testvar' => 'foo'));
	}

	public function testPartialWithIndirectVars(){
		Load::getInstance()->setLayout('../Lib/wolxXxMVC/tests/_src/');
		$this->expectOutputString('foo');
		Load::getInstance()->partial('testview2', array('testvar' => 'foo'), true);
	}

	public function testPartialWithoutHavingView(){
		Load::getInstance()->setLayout('../Lib/wolxXxMVC/tests/_src/');
		$this->expectOutputString('partial testview2XYS nicht gefunden! uri = localhost');
		Load::getInstance()->partial('testview2XYS', array('testvar' => 'foo'), true);
	}

	public function testView(){
		Load::getInstance()->setLayout('../Lib/wolxXxMVC/tests/_src/nix');
		Load::getInstance()->set('testvar', 'foo');
		Load::getInstance()->view('testview');
	}

	public function testSetterAndGetter(){
		Load::getInstance()->clearBuffer();
		Load::clearInstance();
		Load::getInstance()->set('hallo', 'ja?');
		$this->assertEquals(Load::getInstance()->get('hallo'), 'ja?');
		$this->assertEquals(Load::getInstance()->get('halloo'), null);
	}

	public function testLayout(){
		$this->assertEquals(Load::getInstance()->getLayout(), 'main');
		Load::getInstance()->setLayout('nix');
		$this->assertEquals(Load::getInstance()->getLayout(), 'nix');
		Load::getInstance()->setLayout();
		$this->assertEquals(Load::getInstance()->getLayout(), 'main');
	}

	public function testViewExists(){
		$this->assertEquals(Load::getInstance()->viewExists('soeinenviewhabenwirgarantiertnichdigga'), false);
		Load::getInstance()->setLayout('muhahaha');
		$this->assertEquals(Load::getInstance()->viewExists('soeinenviewhabenwirgarantiertnichdigga'), false);
	}

	public function testAddJavascriptFile(){
		Load::getInstance()->addJavascriptFile('foo');
		$this->assertSame(array('foo.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddJavascriptFileAtTop(){
		Load::getInstance()->addJavascriptFile('foo');
		Load::getInstance()->addJavascriptFile('bar', true);
		$this->assertSame(array('bar.js', 'foo.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddJavascriptFiles(){
		Load::getInstance()->addJavascriptFiles(array('foo', 'bar.js'));
		$this->assertSame(array('foo.js', 'bar.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddAndGetJavascript(){
		Load::getInstance()->addJavascript('alert("fooo!")');
		Load::getInstance()->addJavascript('alert("baaaar!")', true);
		$this->assertSame('alert("baaaar!")'.PHP_EOL.PHP_EOL.'alert("fooo!")', Load::getInstance()->getJavascript());
	}

	public function testGetMergedJavascript(){
		Load::getInstance()->addJavascript('alert("fooo!")');
		Load::getInstance()->addJavascript('alert("baaaar!")', true);
		$this->assertSame(PHP_EOL.'alert("baaaar!")'.PHP_EOL.PHP_EOL.'alert("fooo!")', Load::getInstance()->getMergedJavascript());

		Load::getInstance()->addJavascriptFile('_src/test.js');
		$this->assertSame("alert('huhu from test.js!');".PHP_EOL.'alert("baaaar!")'.PHP_EOL.PHP_EOL.'alert("fooo!")', Load::getInstance()->getMergedJavascript());
	}

	public function testJavascript(){
		Load::getInstance()->clearAllJavascript();
	}

	public function testCss(){
		Load::getInstance()->clearAllCss();
		$this->assertSame(PHP_EOL, Load::getInstance()->getMergedCss());
		Load::getInstance()->addCssFile('_src/test.css');
		Load::getInstance()->addCssFiles(array('_src/test.css'));
		Load::getInstance()->addCssFiles(array('_src/test.css', '_src/test'));
		Load::getInstance()->addCssFile('_src/test', true);
		$this->assertSame(array('_src/test.css'), Load::getInstance()->getCssFiles());
		Load::getInstance()->clearAllCss()->addCss('a{display: none;}');
		$this->assertSame(PHP_EOL.PHP_EOL.'a{display: none;}', Load::getInstance()->getMergedCss());
		Load::getInstance()->clearAllCss()->addCss('a{display: none;}')->addCss('a{display:block}', true);
		$this->assertSame(PHP_EOL.'a{display:block}'.PHP_EOL.PHP_EOL.'a{display: none;}', Load::getInstance()->getMergedCss());
		$this->assertSame('a{display:block}'.PHP_EOL.PHP_EOL.'a{display: none;}', Load::getInstance()->getCss());
	}

	public function testForCoverage(){
		Load::getInstance()->debug();
	}


}