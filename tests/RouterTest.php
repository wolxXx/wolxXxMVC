<?
/**
 * @codeCoverageIgnore
 */
class RouterTest extends PHPUnit_Framework_TestCase{
	public function testReplace(){
		$this->assertEquals(Router::clearForUrl('hallo welt'), 'hallo-welt');
		$this->assertEquals(Router::clearForUrl('hallo_welt'), 'hallo_welt');
		$this->assertEquals(Router::clearForUrl('Hällö Wörlt!'), 'Haelloe-Woerlt');
		$this->assertEquals(Router::clearForUrl('Äüßerungen dieser Art sind ausgeschloßen, dummsau!'), 'Aeuesserungen-dieser-Art-sind-ausgeschlossen-dummsau');
	}

	public function testAddRoutes(){
		$router = new Router();
		$router->addRoute('test', 'hallo');
		$this->assertContains('hallo', $router->getRoute('test'));
		$this->assertContains('hallo', $router->getAllRoutes());
		$this->assertSame(array('test' => 'hallo'), $router->getAllRoutes());
		$this->assertEquals($router->getRoute('hamwanet'), null);
	}
}