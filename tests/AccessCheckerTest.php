<?
/**
 * @codeCoverageIgnore
 */
class AccessCheckerTest extends  PHPUnit_Framework_TestCase{
	public function testClearRules(){
		$accessChecker = new AccessChecker();
		$accessChecker->clearRules();
		$this->assertEmpty($accessChecker->getRules());
	}

	public function testSetUserLevel(){
		$accessChecker = new AccessChecker();
		$accessChecker->setUserLevel(2);
		$this->assertSame(2, $accessChecker->getUserLevel());
	}

	public function testSetIsLoggedIn(){
		$accessChecker = new AccessChecker();
		$accessChecker->setUserIsLoggedIn(true);
		$this->assertTrue($accessChecker->isUserLoggedIn());
	}

	public function testGeneral(){
		$accessChecker = new AccessChecker();
		$accessChecker->addRule(new AccessRule('*'));
		$this->assertTrue($accessChecker->checkAccess('pewpew'));
		$this->assertFalse($accessChecker->requiresAuth('pewpew'));
	}

	public function testRemoveRule(){
		$accessChecker = new AccessChecker();
		$accessChecker->addRule(new AccessRule('pewpew', true, 3));
		$accessChecker->removeRule('pewpew');
		$this->assertFalse($accessChecker->requiresAuth('pewpew'));
	}

	public function testHasRule(){
		$accessChecker = new AccessChecker();
		$accessChecker->addRule(new AccessRule('pewpew', true, 3));
		$this->assertTrue($accessChecker->hasRuleForAction('pewpew'));
	}

	public function testCheckForLogin(){
		$accessChecker = new AccessChecker();
		$accessChecker->addRule(new AccessRule('pewpew', true, 3));
		$this->assertFalse($accessChecker->checkAccess('pewpew'));
	}

	public function testCheckForLevel(){
		$accessChecker = new AccessChecker(true, 2);
		$accessChecker->addRule(new AccessRule('pewpew', true, 3));
		$this->assertFalse($accessChecker->checkAccess('pewpew'));
	}

	public function testCheckForLevelAndSuccess(){
		$accessChecker = new AccessChecker(true, 3);
		$accessChecker->addRule(new AccessRule('pewpew', true, 3));
		$this->assertTrue($accessChecker->checkAccess('pewpew'));
	}
}