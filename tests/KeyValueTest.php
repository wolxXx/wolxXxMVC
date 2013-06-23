<?
class KeyValueTest extends  PHPUnit_Framework_TestCase{
	protected function getStore(){
		return new KeyValueStore();
	}

	public function testEmptyOnCreation(){
		$store = $this->getStore();
		$this->assertEmpty($store->getData());
	}

	/**
	 * @expectedException Exception
	 */
	public function testKeyNotFound(){
		$store = $this->getStore();
		$store->get('foo');
	}

	public function testSetWholeArray(){
		$array = array('foo' =>  'bar');
		$store = $this->getStore();
		$store->setData($array);
		$this->assertSame('bar', $store->get('foo'));
	}

	public function testAddDataNonOverwriting(){
		$store = $this->getStore();
		$store->set('foo', 'bar');
		$store->set('fooo', 'baar');
		$store->addData(array('foo' => 'new'), false);
		$this->assertSame('bar', $store->get('foo'));
		$this->assertSame('baar', $store->get('fooo'));
	}

	public function testAddDataOverwriting(){
		$store = $this->getStore();
		$store->set('foo', 'bar');
		$store->set('fooo', 'baar');
		$store->addData(array('foo' => 'new'));
		$this->assertSame('new', $store->get('foo'));
		$this->assertSame('baar', $store->get('fooo'));
	}

	public function testGetData(){
		$store = $this->getStore();
		$store->set('foo', 'bar');
		$this->assertSame(array('foo' => 'bar'), $store->getData());
	}

}