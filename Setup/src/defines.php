<?
/**
 * this is the place to drop defines
 * yout can put them also into the AppConfig or HostConfig
 * but you can not delete this file!
 *
 * feel free to remove all these defines!
 */
defined('GODFATHEROFLINUX') or define('GODFATHEROFLINUX', 'LINUS TORVALDS!!!');

/*
 * states in the database
 */
defined('USER_STATUS_PENDING') or define('USER_STATUS_PENDING', 0);
defined('USER_STATUS_ACTIVATED') or define('USER_STATUS_ACTIVATED', 1);
defined('USER_STATUS_BANNED') or define('USER_STATUS_BANNED', 2);

defined('USER_TYPE_USUAL') or define('USER_TYPE_USUAL', 0);
defined('USER_TYPE_EDITOR') or define('USER_TYPE_EDITOR', 1);
defined('USER_TYPE_ADMIN') or define('USER_TYPE_ADMIN', 2);

/*
 * how long a user shall be banned. in seconds
 */

defined('BAN_TIME') or define('BAN_TIME', 1337);

/*
 * the admin email adress
 */

defined('ADMIN_EMAIL') or define('ADMIN_EMAIL', 'email@liame@de');


/*
 * keys for auth class
*/
defined('LOGGEDIN') or define('LOGGEDIN', 'Auth.loggedIn');
defined('USER') or define('USER', 'Auth.user');
defined('CREDENTIALUSERID') or define('CREDENTIALUSERID', 'Auth.credentialsUserId');
defined('CREDENTIALUSERACCESS') or define('CREDENTIALUSERACCESS', 'Auth.credentialsUserAccess');
defined('ACTIVATEUSERBANNING') or define('ACTIVATEUSERBANNING', 'Auth.activateUserBanning');
defined('FAILEDLOGINS') or define('FAILEDLOGINS', 'Auth.failedLogins');
defined('BANNED') or define('BANNED', 'Auth.banned');
defined('USERBANTIME') or define('USERBANTIME', 'Auth.userBanTime');