<?
/**
 * @codeCoverageIgnore
 */
class RequestTest extends  PHPUnit_Framework_TestCase{
	protected function getRequest(){
		return new Request();
	}
	public function testIsMobileForDefault(){
		$request = $this->getRequest();
		$this->assertFalse($request->isMobile());
	}

	public function testIsMobileForSetToMobile(){
		Stack::getInstance()->set('version', 'mobile');
		$request = $this->getRequest();
		$this->assertTrue($request->isMobile());
	}

	public function testIsPostWithoutPost(){
		$request = $this->getRequest();
		$this->assertFalse($request->isPost());
	}

	public function testIsPostWithPost(){
		$_POST['foo'] = 'bar';
		$request = $this->getRequest();
		$this->assertTrue($request->isPost());
	}

	public function testIsAjaxWithoutAjaxPost(){
		$request = $this->getRequest();
		$this->assertFalse($request->isAjax());
	}

	public function testIsAjaxWithAjaxPost(){
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'something jibbedibbe';
		$request = $this->getRequest();
		$this->assertTrue($request->isAjax());
	}

	public function testPostLog(){
		$this->getRequest()->postLog('log/postlog');
		$_POST['foo'] = 'bar';
		$_POST['pass'] = 'bar';
		$_POST['base64data'] = base64_decode('bar');
		$_POST['foo'] = array('foo', 'bar', 'pewpew');
		$_POST['foo']['foo2'] = 'bar';
		$this->getRequest()->postLog('testpost');
	}
}
