<?
/**
 * @codeCoverageIgnore
 */
class DataObjectTest extends  PHPUnit_Framework_TestCase{
	public function testDebug(){
		$this->expectOutputRegex('/GET/');
		$this->expectOutputRegex('/POST/');
		$this->expectOutputRegex('/FILES/');
		$dataObject = new DataObject();
		$dataObject->debug();

	}
	public function testRemoveKey(){
		$_GET['foo'] = 'bar';
		$dataObject = new DataObject();
		$this->assertSame('bar', $dataObject->get('foo'));
		$dataObject->removeKey('foo');
		$this->assertSame('', $dataObject->getSavely('foo', ''));
	}

	public function testGetSavely(){
		$dataObject = new DataObject();
		$this->assertSame('', $dataObject->getSavely('foobar', ''));
	}

	public function testPostData(){
		$dataObject = new DataObject();
		$this->assertSame(array(), $dataObject->getRawPOST());
	}

	public function testGetDataForGet(){
		$dataObject = new DataObject();
		$this->assertSame(array(), $dataObject->getRawGET());
		$_GET['foo'] = 'bar';
		$dataObject = new DataObject();
		$this->assertSame(array('foo' => 'bar'), $dataObject->getRawGET());
	}

	public function testFiles(){
		$_FILES[] = array(
			0 => 'header.png',
			'name' => 'header.png',
			1 => 'image/png',
			'type' => 'image/png',
			2 => __DIR__.DIRECTORY_SEPARATOR.'_src/header.png',
			'tmp_name' => __DIR__.DIRECTORY_SEPARATOR.'_src/header.png',
			3 => 0,
			'error' => 0,
			4 => 1234,
			'size' => 1234
		);
		$dataObject = new DataObject();
		$files = $dataObject->getFiles();
		$this->assertSame(1, sizeof($files));
		$this->assertSame('.png', $files[0]->extension);
	}

	public function testGetData(){
		$_POST['huhu'] = 'katchew';
		$_GET['pewpew'] = 'katchew2';
		$_POST['pewpew'][] = 'katchew3';
		$_POST['pewpew'][] = 'katchew4';
		$dataObject = new DataObject();
		$this->assertSame(array(
			'pewpew' => array (
				0 => 'katchew3',
				1 => 'katchew4'
			),
			'huhu' => 'katchew'), $dataObject->getData());
	}

	public function testHasDataForKey(){
		$_POST['huhu'] = 'katchew';
		$_GET['pewpew'] = 'katchew2';
		$dataObject = new DataObject();
		$this->assertFalse($dataObject->hasDataForKey('nope!'));
		$this->assertSame('katchew', $dataObject->huhu);
		$this->assertSame('katchew2', $dataObject->pewpew);
	}

	/**
	 * @expectedException KeyNotExistsInDataObject
	 */
	public function testHamwaNet(){
		$dataObject = new DataObject();
		$dataObject->huhu;
	}
}
