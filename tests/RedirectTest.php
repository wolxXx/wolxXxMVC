<?

/**
 * @codeCoverageIgnore
 */
class RedirectTest extends  PHPUnit_Framework_TestCase{
	protected static $url = '/foo';
	public function testFooAsUrl(){
		$redirect = new Redirect(self::$url);
		$this->assertSame(self::$url, $redirect->getUrl());
	}

	public function testMethod(){
		$redirect = new Redirect(self::$url, Redirect::$moved);
		$this->assertSame(self::$url, $redirect->getUrl());
		$this->assertSame(Redirect::$moved, $redirect->getMethod());
	}

	public function testSetUrl(){
		$redirect = new Redirect();
		$redirect->setUrl(self::$url);
		$this->assertSame(self::$url, $redirect->getUrl());
	}

	public function testDefaultUrl(){
		$redirect = new Redirect();
		$this->assertSame(null, $redirect->getUrl());
	}

	public function testDefaultMethod(){
		$redirect = new Redirect();
		$this->assertSame(Redirect::$redirect, $redirect->getMethod());
	}

	/**
	 * @covers Redirect::redirect
	 */
	public function testRedirect(){
		$redirect = new Redirect();
		$redirect->setUrl(self::$url);
		$redirect->setMethod(Redirect::$moved);

		$test = $this;
		$mock = $this->getMock('Helper', array('moved'))
			->expects($this->once())
			->method('moved')
			->will($this->returnValue(null));

		$redirect->redirect();
	}

	/**
	 * @expectedException Exception
	 */
	public function testRedirectWithoutHavingSetUrl(){
		$redirect = new Redirect();
		$redirect->redirect();
	}
}