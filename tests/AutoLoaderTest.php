<?
/**
 * @codeCoverageIgnore
 */
class AutoLoaderTest extends  PHPUnit_Framework_TestCase{
	public function testLoadClassUcfirst(){
		$autoloader = new AutoLoader();
		$autoloader->loadClass('coreController');
	}

	public function testLoadClass(){
		$autoloader = new AutoLoader();
		$autoloader->loadClass('CoreController');
	}

	public function testLoadClassLowerFirst(){
		$autoloader = new AutoLoader();
		$autoloader->loadClass('Functions');
	}

	public function testLoadClassHiddenClass(){
		$autoloader = new AutoLoader();
		$autoloader->loadClass('Test');
	}

	public function testStaticIsLoadable(){
		$this->assertTrue(AutoLoader::isLoadable('Helper'));
	}

	public function testStaticIsLoadableForNonExisting(){
		$this->assertFalse(AutoLoader::isLoadable('pewpewHelper'));
	}
}