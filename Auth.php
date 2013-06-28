<?
/**
 * auth class
 * should be used as static class, no chance to instanciate
 * has methods: login, logout, isLoggedIn, getUserData
 *
 * the data is saved in the stack, so it is available after page refresh
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 */
abstract class Auth{
	/**
	 * the user
	 *
	 * @var Result
	 */
	public static $User;

	/**
	 * getter for the stack
	 *
	 * @param string $key
	 * @return mixed
	 */
	private static function get($key){
		return Stack::getInstance()->get($key);
	}
	/**
	 * setter for the stack
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	private static function set($key, $value){
		Stack::getInstance()->set($key, $value);
	}

	/**
	 * clears all auth session data
	 */
	public static function logout(){
		self::set(LOGGEDIN, false);
		self::set(USER, null);
		self::set(FAILEDLOGINS, 0);
	}

	/**
	 * creates auth session data
	 * does NOT prove the integriry (yet, maybe later..)
	 *
	 * @param Result $result
	 * @todo remove redirects!!!
	 */
	public static function login($result = null){
		$model = new Model();
		$dataObject = new DataObject();

		/**
		 * get the user from the database if result does not provide a database result
		 */
		if(null === $result){
			$result = $model->findOne('user', $dataObject->get(self::get(CREDENTIALUSERID)), self::get(CREDENTIALUSERID));
		}
		/**
		 * is a user found with this credentials?
		 */
		if(null === $result){
			/**
			 * shall the ban-mode be activated?
			 */
			if(true === self::get(ACTIVATEUSERBANNING)){
				self::set(FAILEDLOGINS, self::get(FAILEDLOGINS) + 1);
				if(self::get(FAILEDLOGINS) > 2){
					self::set(BANNED, time() + self::get(USERBANTIME));
					self::set(FAILEDLOGINS, 0);
					if(true === method_exists(Helper, 'sendBanMail')){
						Helper::sendBanMail();
					}
					Helper::redirect('/auth/login');
				}
			}
			/**
			 * give feedback
			 */
			Helper::addSplash('Unbekannter Nutzer.');
			Helper::redirect('/auth/login');
		}
		/**
		 * well a user was found, is his access matching to the database one's
		 */

		if($result->password !== md5($dataObject->get(self::get(CREDENTIALUSERACCESS)))){
			if(true === self::get(ACTIVATEUSERBANNING)){
				self::set(FAILEDLOGINS, self::get(FAILEDLOGINS) + 1);
				if(self::get(FAILEDLOGINS) > 2){
					self::set(BANNED, time() + BAN_TIME);
					self::set(FAILEDLOGINS, 0);
					if(method_exists(Helper, 'sendBanMail')){
						Helper::sendBanMail();
						Helper::sendBanMail($result);
					}
					$password = Helper::generatePassword();
					$update = new UpdateObject('user', $result->id);
					$update->password = md5($password);
					$update->update();
					Helper::redirect('/auth/login');
				}
			}
			Helper::addSplash('Falsches Passwort!');
			Helper::redirect('/auth/login');
		}
		if(USER_STATUS_BANNED == $result->status){
			Helper::redirect('/error/banned');
		}
		if(USER_STATUS_PENDING == $result->status){
			Helper::redirect('/auth/pending');
		}
		unset($result->password);
		self::set(LOGGEDIN, true);
		self::setUser($result);
		$update = new UpdateObject('user', $result->id);
		$update->lastlog = Helper::getDate();
		$update->update();
	}

	/**
	 * setter for the user
	 *
	 * @param Result $user
	 */
	public static function setUser($user){
		self::set(USER, $user);
	}

	/**
	 * bans a user the time to ban was set in the defines file by BAN_TIME
	 */
	public static function ban(){
		self::set(BANNED, time() + BAN_TIME);
		Helper::refresh();
	}

	/**
	 * unbans a user
	 */
	public static function unBan(){
		self::set(BANNED, time() -1);
	}

	/**
	 * checks if a user is banned
	 *
	 * @return boolean
	 */
	public static function isBanned(){
		return self::getRemainingBanTime() > 0;
	}

	/**
	 * returns the seconds the user will be banned
	 *
	 * @return integer
	 */
	public static function getRemainingBanTime(){
		return self::get(BANNED) - time();
	}

	/**
	 * setter for the logged in state
	 *
	 * @param boolean $isLoggedIn
	 */
	public static function setIsLoggedIn($isLoggedIn = true){
		self::set(LOGGEDIN, $isLoggedIn);
	}

	/**
	 * checks if user is logged in
	 *
	 * @return boolean
	 */
	public static function isLoggedIn(){
		return true == self::get(LOGGEDIN);
	}

	/**
	 * checks if user has access to the level
	 * eg requested page (admin) has level 3, if user has 2 it returns false
	 * if requested page (home) has access level 0, if user has at least 1, it returns true
	 *
	 * @param integer $level
	 */
	public static function hasAccess($level){
		if(false === self::isLoggedIn()){
			return false;
		}
		return $level <= Auth::getUserType();
	}

	/**
	 * returns the id of the current loggedin user
	 *
	 * @return integer
	 * @throws Exception
	 */
	public static function getUserId(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s id!');
		}
		return self::get(USER)->id;
	}

	/**
	 * returns the type of the currently logged in user
	 *
	 * @return integer
	 * @throws Exception
	 */
	public static function getUserType(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s type!');
		}
		return self::get(USER)->type;
	}

	/**
	 * returns the nick name of the currently logged in user
	 *
	 * @throws Exception
	 * @return string
	 */
	public static function getUserNick(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s nick!');
		}
		return self::get(USER)->nick;
	}


	/**
	 * returns the email of the currently logged in user
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function getUserEmail(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s email!');
		}
		return self::get(USER)->email;
	}

	/**
	 * returns the status of the currently logged in user
	 *
	 * @return integer
	 * @throws Exception
	 */
	public static function getUserStatus(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s status!');
		}
		return self::get(USER)->status;
	}

	/**
	 * returns the date of the last login of the currently logged in user
	 *
	 * @return DateTime
	 * @throws Exception
	 */
	public static function getUserLastLogin(){
		if(false === self::isLoggedIn()){
			throw new Exception('User is not logged in! cannot return user\'s last login date!');
		}
		return self::get(USER)->lastlog;
	}

	/**
	 * returns the amount of failed logins of the current user
	 *
	 * @return integer
	 */
	public static function getUserFailedLogins(){
		return self::get(FAILEDLOGINS);
	}
}