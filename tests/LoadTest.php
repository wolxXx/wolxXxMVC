<?
/**
 * @codeCoverageIgnore
 */
class LoadTest extends  PHPUnit_Framework_TestCase{
	public function setUp(){
		Stack::getInstance()->set('controller', 'cms');
		Load::getInstance()->setLayoutPath(__DIR__.DIRECTORY_SEPARATOR.'_src');
		Load::getInstance()->setViewPath(__DIR__.DIRECTORY_SEPARATOR.'_src');
		Load::getInstance()->clearAllJavascript()->clearAllCss();
	}

	/**
	 * @expectedException Exception
	 */
	public function testViewNotExists(){
		Load::getInstance()->view();
	}

	public function testDebug(){
		Load::getInstance()->set('isAjax', true);
		Load::getInstance()->debug();
	}

	public function testAjaxRequest(){
		$staticview = 'staticview';
		$this->expectOutputString(file_get_contents(Load::getInstance()->getViewPath().$staticview.'.php'));
		Load::getInstance()->set('isAjax', true);
		Load::getInstance()->view($staticview);
	}

	/**
	 * @expectedException Exception
	 */
	public function testLayoutDoesNotExist(){
		Load::getInstance()->setLayout('hamwadochehnich');
	}

	public function testSetLayoutPath(){
		$path = __DIR__.DIRECTORY_SEPARATOR.'_src';
		$this->assertSame($path.DIRECTORY_SEPARATOR, Load::getInstance()->getLayoutPath());
	}

	public function testPartialWithDirectVars(){
		$this->expectOutputString('foo');
		Load::getInstance()->partial('testview', array('testvar' => 'foo'));
	}

	public function testPartialWithIndirectVars(){
		$this->expectOutputString('foo');
		Load::getInstance()->partial('testview2', array('testvar' => 'foo'), true);
	}

	public function testPartialWithoutHavingView(){
		$this->expectOutputString('partial "testview2XYS" not found!');
		Load::getInstance()->partial('testview2XYS', array('testvar' => 'foo'), true);
	}

	public function testSetterAndGetter(){
		#Load::getInstance()->clearBuffer();
		Load::clearInstance();
		Load::getInstance()->set('hallo', 'ja?');
		$this->assertEquals(Load::getInstance()->get('hallo'), 'ja?');
		$this->assertEquals(Load::getInstance()->get('halloo'), null);
	}

	public function testDefaultLayout(){
		$this->assertEquals(Load::getInstance()->getLayout(), 'main');
	}

	public function testLayout2(){
		Load::getInstance()->setLayout('nix');
		$this->assertEquals(Load::getInstance()->getLayout(), 'nix');
	}

	public function testSetDefaultLayout(){
		Load::getInstance()->setLayout();
		$this->assertEquals(Load::getInstance()->getLayout(), 'main');
	}

	public function testViewExists(){
		$this->assertFalse(Load::getInstance()->viewExists('soeinenviewhabenwirgarantiertnichdigga'));
	}

	public function testAddJavascriptFile(){
		Load::getInstance()->clearAllJavascript()->clearAllCss();
		Load::getInstance()->addJavascriptFile('foo');
		$this->assertSame(array('foo.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddJavascriptFileAtTop(){
		Load::getInstance()->clearAllJavascript();
		Load::getInstance()->addJavascriptFile('foo');
		Load::getInstance()->addJavascriptFile('bar', true);
		$this->assertSame(array('bar.js', 'foo.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddJavascriptFiles(){
		Load::getInstance()->clearAllJavascript()->clearAllCss();
		Load::getInstance()->addJavascriptFiles(array('foo', 'bar.js'));
		$this->assertSame(array('foo.js', 'bar.js'), Load::getInstance()->getJavascriptFiles());
	}

	public function testAddAndGetJavascript(){
		Load::getInstance()->addJavascript('alert("fooo!")');
		Load::getInstance()->addJavascript('alert("baaaar!")', true);
		$this->assertSame('alert("baaaar!")'.PHP_EOL.PHP_EOL.'alert("fooo!")', Load::getInstance()->getJavascript());
	}

	public function testGetMergedJavascript(){
		Load::clearInstance();
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
}