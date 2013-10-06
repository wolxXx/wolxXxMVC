<?
/**
 * @codeCoverageIgnore
 */
class MockUser extends Result{
	public $id = 1337;
	public $nick = 'Linus Torvalds';
	public $email = 'god@linux.org';
	public $lastlog = '2009-04-24 18:00:00';
	public $type = 3;
	public $status = 2;
	public $password = 'e206a54e97690cce50cc872dd70ee896';
}
/**
 * @codeCoverageIgnore
 */
class AuthTest extends  PHPUnit_Framework_TestCase{

	public function testIsUserPasswordOk(){
		$this->assertFalse(Auth::isUserPasswordOk('blubb', new MockUser()));
		$user = new MockUser();
		$user->password = Auth::hashPassword('foobar');
		$this->assertTrue(Auth::isUserPasswordOk('foobar', $user));
	}

	public function testHashPassword(){
		$this->assertSame(md5('foobar'), Auth::hashPassword('foobar'));
	}

	public function testSaltingPassword(){
		$this->assertSame('foobar', Auth::saltPassword('foobar'));
	}

	public function testLogin(){
		Auth::logout();
		$_POST[Stack::getInstance()->get(CREDENTIALUSERID)] = 'god@linux.org';
		$_POST[Stack::getInstance()->get(CREDENTIALUSERACCESS)] = 'linux';
		Auth::login(new MockUser());
	}

	public function testIsLoggedInAfterLogout(){
		Auth::logout();
		$this->assertEquals(false, Auth::isLoggedIn());
	}

	public function testIsLoggedInAfterManualLogin(){
		Auth::setIsLoggedIn();
		$this->assertEquals(true, Auth::isLoggedIn());
	}

	public function testSetUser(){
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertEquals('1337', Auth::getUserId());
		$this->assertEquals('Linus Torvalds', Auth::getUserNick());
		$this->assertEquals('god@linux.org', Auth::getUserEmail());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserIdWithoutLogin(){
		Auth::logout();
		$this->assertEquals('1337', Auth::getUserId());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserEmailWithoutLogin(){
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserEmail());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserNickWithoutLogin(){
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserNick());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserLastLoginWithoutLogin(){
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserLastLogin());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserTypeWithoutLogin(){
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserType());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetStatusTypeWithoutLogin(){
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserStatus());
	}

	public function testBan(){
		Auth::ban();
		$this->assertTrue(Auth::isBanned());
	}

	public function testUnban(){
		Auth::unban();
		$this->assertFalse(Auth::isBanned());
	}

	public function testHasAccessWithoutLogin(){
		Auth::logout();
		$this->assertFalse(Auth::hasAccess(3));
	}

	public function testHasAccessWithLoginButLowType(){
		Auth::logout();
		$user = new MockUser();
		$user->type = 0;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertFalse(Auth::hasAccess(3));
	}

	public function testHasAccessWithLoginWithHigherType(){
		Auth::logout();
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertTrue(Auth::hasAccess(1));
	}

	public function testHasAccessWithLoginWithMuchHigherType(){
		Auth::logout();
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertTrue(Auth::hasAccess(2));
	}

	public function testUserStatusActivated(){
		Auth::logout();
		$user = new MockUser();
		$user->status = 1;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserStatus(), 1);
	}

	public function testUserStatusBanned(){
		Auth::logout();
		$user = new MockUser();
		$user->status = 2;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserStatus(), 2);
	}

	public function testUserLastLogin(){
		Auth::logout();
		$user = new MockUser();
		$user->status = 1;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserLastLogin(), '2009-04-24 18:00:00');
	}

	public function testFailedLoginsAfterLogout(){
		Auth::logout();
		$this->assertSame(Auth::getUserFailedLogins(), 0);
	}

	public function testFailedLogins(){
		$_POST[Stack::getInstance()->get(CREDENTIALUSERID)] = 'god@linux.org';
		$_POST[Stack::getInstance()->get(CREDENTIALUSERACCESS)] = 'linux';
		Auth::logout();
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$this->assertSame(Auth::getUserFailedLogins(), true === Stack::getInstance()->get(ACTIVATEUSERBANNING)? 1 : 0);
	}

	public function testMultiFailedLogins(){
		$_POST[Stack::getInstance()->get(CREDENTIALUSERID)] = 'god@linux.org';
		$_POST[Stack::getInstance()->get(CREDENTIALUSERACCESS)] = 'linux';
		Auth::logout();
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$this->assertSame(Auth::getUserFailedLogins(), true === Stack::getInstance()->get(ACTIVATEUSERBANNING)? 2 : 0);
	}

	public function testFailedLoginsToBeZeroForValidUser(){
		$_POST[Stack::getInstance()->get(CREDENTIALUSERID)] = 'god@linux.org';
		$_POST[Stack::getInstance()->get(CREDENTIALUSERACCESS)] = 'linux';
		Auth::logout();
		Auth::login(new MockUser());
		$this->assertSame(Auth::getUserFailedLogins(), 0);
	}

	public function testLoginForValidUser(){
		Auth::logout();
		$_POST[Stack::getInstance()->get(CREDENTIALUSERID)] = 'god@linux.org';
		$_POST[Stack::getInstance()->get(CREDENTIALUSERACCESS)] = 'linux';
		Auth::login(new MockUser());
		$this->assertTrue(Auth::isLoggedIn());
	}

}