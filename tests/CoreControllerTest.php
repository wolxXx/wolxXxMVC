<?
/**
 * @codeCoverageIgnore
 */
class JustAnotherTestController extends CoreController{
	public function testAction(){
		$this->registerRedirect('/pewpew', Redirect::$redirect);
	}

	public function anotherTestAction(){
		$this->registerRedirect(new Redirect('/pewpew'), Redirect::$moved);
	}

	public function pewpewAction(){}
}
/**
 * @codeCoverageIgnore
 */
class AnotherTestController extends CoreController{
	public function indexAction(){}

	public function testAction(){
		$this->registerRedirect('/pewpew', Redirect::$redirect);
	}

	public function anotherTestAction(){
		$this->registerRedirect(new Redirect('/pewpew'), Redirect::$moved);
	}

	public function pewpewAction(){}
}
/**
 * @codeCoverageIgnore
 */
class TestController extends CoreController{
	public function testAction(){
		$this->registerRedirect('/pewpew', Redirect::$redirect);
	}

	public function anotherTestAction(){
		$this->registerRedirect(new Redirect('/pewpew'), Redirect::$moved);
	}

	public function pewpewAction(){}

	/**
	 * (non-PHPdoc)
	 * @see CoreController::setActionAndView()
	 */
	public function setActionAndView(){
		$this->view = 'ajax';
		$this->action = 'pewpew';
	}
}
/**
 * @codeCoverageIgnore
 */
class CoreControllerTest extends  PHPUnit_Framework_TestCase{
	public function tearDown(){
		if(file_exists(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'anothercontroller'.DIRECTORY_SEPARATOR.'pewpew2.php')){
			unlink(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'anothercontroller'.DIRECTORY_SEPARATOR.'pewpew2.php');
		}
		if(is_dir(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'anothercontroller'.DIRECTORY_SEPARATOR)){
			rmdir(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'anothercontroller'.DIRECTORY_SEPARATOR);
		}
	}

	public function testInit(){
		$controller = new TestController();
	}

	public function testInitForMobile(){
		Stack::getInstance()->set('version', 'mobile');
		$controller = new TestController();
		$this->assertSame('mobile', Load::getInstance()->getLayout());
	}

	public function testRegisterRedirect(){
		$controller = new TestController();
		$controller->testAction();
		$this->assertNotNull($controller->getRegisteredRedirect());
		$this->assertSame('/pewpew', $controller->getRegisteredRedirect()->getUrl());
	}

	public function testRegisterRedirectWithProvidedRedirectObject(){
		$controller = new TestController();
		$controller->anotherTestAction();
		$this->assertNotNull($controller->getRegisteredRedirect());
		$this->assertSame('/pewpew', $controller->getRegisteredRedirect()->getUrl());
	}

	public function testDefaultAccessRules(){
		$controller = new TestController();
		$controller->setAccessRules();
		$rules = $controller->getAccessChecker()->getRules();
		$rule = reset($rules);
		$this->assertSame(1, sizeof($rules));
		$this->assertFalse($rule->isAuthNeeded());
		$this->assertSame('*', $rule->getActionName());
	}

	public function testDefaultAccessRulesForLoggedIn(){
		Auth::setIsLoggedIn(true);
		$user = new stdClass();
		$user->id = 0;
		$user->type = USER_TYPE_ADMIN;
		$user->nick = 'linus torvalds';
		Auth::setUser($user);
		$controller = new TestController();
		$controller->setAccessRules();
		$rules = $controller->getAccessChecker()->getRules();
		$rule = reset($rules);
		$this->assertSame(1, sizeof($rules));
		$this->assertFalse($rule->isAuthNeeded());
		$this->assertSame('*', $rule->getActionName());
	}

	public function testDefaultAccessChecker(){
		$controller = new TestController();
		$controller->setAccessRules();
		$controller->checkAccess();
	}

	/**
	 * @expectedException AuthRequestedException
	 */
	public function testAccessCheckForAuthRequiredButNotLoggedIn(){
		$_SERVER['REQUEST_URI'] = '/test/pewpew/1337';
		Auth::setIsLoggedIn(false);
		#$this->markTestSkipped();
		$controller = new TestController();
		$controller->setActionAndView('test', 'pewpew', '1337');
		$controller->getAccessChecker()->addRule(new AccessRule('pewpew', true, 2));
		$controller->checkAccess();
	}

	/**
	 * @expectedException NotAllowedException
	 */
	public function testAccessCheckForAuthRequiredButLowLevelUser(){
		Auth::setIsLoggedIn(true);
		$user = new stdClass();
		$user->id = 0;
		$user->type = USER_TYPE_EDITOR;
		$user->nick = 'linus torvalds';
		Auth::setUser($user);
		$controller = new TestController();
		$controller->getAccessChecker()->clearRules()->addRule(new AccessRule('pewpew', true, USER_TYPE_ADMIN));
		$controller->setActionAndView('test', 'pewpew', '1337');
		$controller->checkAccess();
	}

	public function testAccessCheckForAuthRequiredAndUserLevelIsOK(){
		Auth::setIsLoggedIn(true);
		$user = new stdClass();
		$user->id = 0;
		$user->type = USER_TYPE_ADMIN;
		$user->nick = 'linus torvalds';
		Auth::setUser($user);
		$controller = new TestController();
		$controller->getAccessChecker()->clearRules()->addRule(new AccessRule('pewpew', true, USER_TYPE_ADMIN));
		$controller->setActionAndView('test', 'pewpew', '1337');
		$controller->checkAccess();
	}

	public function testFileObjectsByIndexButNoFiles(){
		$controller = new TestController();
		$files = $controller->getFileUploadObjectsByIndex('hamwanet');
		$this->assertEmpty($files);
	}

	public function testFileObjectsByIndexWithFakeFiles(){
		$_FILES = array(
			'hamwanet' => array(
				'name' => array(
					'header.png'
				),
				'type' => array(
					'image/jpeg'
				),
				'tmp_name' => array(
					__DIR__.DIRECTORY_SEPARATOR.'_src'.DIRECTORY_SEPARATOR.'header.png'
				),
				'error' => array(
					UPLOAD_ERR_OK
				),
				'size' => array(
					1337
				),
			)
		);
		$controller = new TestController();
		$controller->beforeRun();
		$controller->setActionAndView();
		$files = $controller->getFileUploadObjectsByIndex('hamwanet');
		$this->assertSame(1,sizeof($files));
		$this->assertTrue($files[0]->isImage);
	}

	public function testGetRequest(){
		$controller = new TestController();
		$this->assertTrue($controller->getRequest() instanceof Request);
	}

	public function testGetView(){
		$_SERVER['REQUEST_URI'] = '/test/pewpew/1337';
		Auth::setIsLoggedIn(false);
		$controller = new TestController();
		$controller->setActionAndView('test', 'pewpew', '1337');
		$this->assertSame('ajax', $controller->getView());
	}

	public function testGetAction(){
		$_SERVER['REQUEST_URI'] = '/test/pewpew/1337';
		Auth::setIsLoggedIn(false);
		$controller = new TestController();
		$controller->setActionAndView('test', 'pewpew', '1337');
		$this->assertSame('pewpew', $controller->getAction());
	}

	public function testGetPostLogFile(){
		$controller = new TestController();
		$this->assertSame('postlog', $controller->getPostLogFile());
	}

	public function testPostLog(){
		$this->expectOutputString('');
		$controller = new TestController();
		$controller->postlog();
	}

	public function testNoop(){
		$this->expectOutputString('');
		$controller = new TestController();
		$controller->noopAction();
	}

	public function testToString(){
		$controller = new TestController();
		$this->assertSame('TestController', $controller->__toString());
	}

	public function testBeforeRun(){
		$this->expectOutputString('');
		$controller = new TestController();
		$controller->beforeRun();
	}

	public function testAfterRun(){
		$this->expectOutputString('');
		$controller = new TestController();
		$controller->afterRun();
	}

	public function testRun(){
		$this->expectOutputString('');
		$controller = new TestController();
		$controller->setActionAndView();
		$controller->setAccessRules();
		$controller->beforeRun();
		$controller->run();
	}

	public function testDefaultSetActionAndView(){
		$controller = new AnotherTestController();
		$controller->setActionAndView();
	}

	/**
	 * @expectedException NoViewException
	 */
	public function testDefaultSetActionAndViewButNoView(){
		$controller = new AnotherTestController();
		$controller->setActionAndView('AnotherTest','hamwanet');
	}

	/**
	 * @expectedException NoViewException
	 */
	public function testDefaultSetActionAndViewButNoViewAgain(){
		$controller = new JustAnotherTestController();
		$controller->setActionAndView('');
	}

	public function testDefaultSetActionAndViewWithNo(){
		$controller = new AnotherTestController();
		$controller->setActionAndView('AnotherTest','pewpew');
	}

	public function testDefaultSetActionAndViewWithNotHavingAMethod(){
		Stack::getInstance()->set('controller', 'anothercontroller');
		$controller = new AnotherTestController();
		Load::getInstance()->setLayout();
		mkdir(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.Stack::getInstance()->get('controller').DIRECTORY_SEPARATOR, 0777, true);
		touch(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.Stack::getInstance()->get('controller').DIRECTORY_SEPARATOR.'pewpew2.php');
		$this->assertTrue(is_dir(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'anothercontroller'));
		$this->assertTrue(file_exists(Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.Stack::getInstance()->get('controller').DIRECTORY_SEPARATOR.'pewpew2.php'));

		$this->assertSame('anothercontroller', Stack::getInstance()->get('controller'));
		$this->assertTrue(Load::getInstance()->viewExists('pewpew2'));
		$controller->setActionAndView('AnotherTest','pewpew2');
	}
}