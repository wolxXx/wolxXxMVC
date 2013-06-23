<?
/**
 * Tool collection for daily usage
 * https://www.youtube.com/watch?v=rtBq9QKJTq8
 *
 * @author wolxXx
 * @version 1.4
 * @package wolxXxMVC
 *
 */
class CoreHelper{
	/**
	 * removes all html tags from a given string
	 * @param string $str
	 * @return string
	 */
	public static function removeTagsFromText($text) {
		$str = preg_replace("#<(.*)/(.*)>#iUs", "", $text);
		$str = preg_replace("#<(.*)>#iUs", "", $text);
		return $str;
	}

	/**
	 * removes a single html tag from a given string
	 * @param string $text
	 * @param string $tag
	 * @return string
	 */
	public static function removeSingleTagFromText($text, $tag) {
		$str = preg_replace("#\<".$tag."(.*)>#iUs", "", $text);
		$tag = '/'.$tag;
		$str = preg_replace("#\<".$tag."(.*)>#iUs", "", $str);
		return $str;
	}

	/**
	 * logs a message that the access is deprecated
	 * leave param for having function and class as default!
	 * @param string | null $message
	 */
	public static function logDeprecatedAccess($message = null){
		$trace = debug_backtrace();
		$trace = $trace[1];
		if(null === $message){
			$message = 'called deprecated method '.$trace['function'];
			if(true === isset($trace['class'])){
				$message .= ' in Class '.$trace['class'];
			}
		}
		Helper::logToFile(Helper::getDate().' | '.$message.' on line '.$trace['line'].' in file '.$trace['file'].' | url: '.Helper::getCurrentURL(), 'deprecated');
	}

	/**
	 * sends args to debug method and dies
	 */
	public static function dieDebug(){
		foreach(func_get_args() as $arg){
			Helper::debug($arg);
		}
		die('die in die debug');
	}

	/**
	 * var_dumps all provided elements if debugging is enabled in stack
	 */
	public static function debug(){
		$stack = Stack::getInstance();
		if(false === in_array($stack->get('debug'), array('1', true, 'true'))){
			return;
		}
		$backtrace = debug_backtrace(true);
		$trace = $backtrace[0];
		if('CoreHelper.php' === Helper::getFileName($trace['file'])){
			$trace = $backtrace[1];
		}
		$line = isset($trace['line'])? $trace['line'] : 666;
		$file = isset($trace['file'])? $trace['file'] : 'somewhere';
		echo '<div class="debug"><pre>debug from '.(str_replace(Helper::getDocRoot(), '', $file)).' line '.$line.':</pre>';
		foreach(func_get_args() as $arg){
			var_dump($arg);
		}
		echo '</div>';
	}

	/**
	 * reloads the current site
	 */
	public static function refresh(){
		self::redirect();
	}

	/**
	 * redirects to $url or reloads the site if $url is null
	 * @param string $url
	 */
	public static function redirect($url = null){
		if(null === $url){
			$url = '/';
			if(true === isset($_SERVER['REQUEST_URI'])){
				$url = $_SERVER['REQUEST_URI'];
			}
		}
		header('Location:'.$url);
		die();
	}

	/**
	 * redirects
	 * @param string $url
	 */
	public static function moved($url){
		header("HTTP/1.1 301 Moved Permanently");
		self::redirect($url);
	}

	/**
	 * sends the user back to where he came ;)
	 * the $redirect param is a fallback
	 * @param string $redirect
	 */
	public static function historyBack($redirect = '/'){
		if(true === isset($_SERVER['HTTP_REFERER'])){
			$redirect = $_SERVER['HTTP_REFERER'];
		}
		self::redirect($redirect);
	}

	/**
	 * retrieves the absolute and complete url of the current site
	 * @return string
	 */
	public static function getCurrentURL(){
		if(false === isset($_SERVER['REQUEST_URI']) || false === isset($_SERVER['HTTP_HOST'])){
			return 'localhost';
		}
		return self::getRequestProtocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	/**
	 * retrieves the relative uri of the current site
	 * @return string
	 */
	public static function getCurrentURI(){
		if(false === isset($_SERVER['REQUEST_URI'])){
			return 'localhost';
		}
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * returns https if the current request is a https request, http otherwise
	 * @return string
	 */
	public static function getRequestProtocol(){
		return 'http'.(true === self::requestIsHTTPS()? 's' : '');
	}

	/**
	 * checks if the current request is a https request
	 * @return boolean
	 */
	public static function requestIsHTTPS(){
		return isset($_SERVER['HTTPS']) && in_array($_SERVER['HTTPS'], array('on', '1', true));
	}

	/**
	 * copies an external source to the local file system
	 * eg an image from youtube: http://img.youtube.com/vi/JKPvx38D4GM/default.jpg to files/yt/JKPvx38D4GM.jpg
	 * returns true if the $path is a file after the operation
	 * if the directory does not exist, it creates the directory recursivly
	 * @param string $url
	 * @param string $path
	 * @return boolean
	 */
	public static function saveWebDataToFileSystem($url, $path){
		$directory = str_replace(basename($path), '', $path);
		$directory = preg_replace('/\w+\/\.\.\//', '', $directory);
		if(false === is_dir($directory)){
			mkdir($directory, 0777, true);
		}
		$ch = curl_init($url);
		$fp = fopen($path, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		$result = is_file($path);
		return $result;
	}

	/**
	 * checks the syntax of a given email adress
	 * @param string $mail
	 * @return boolean
	 * @deprecated use isMailSyntaxOk instead!
	 */
	public static function checkMailSyntax($mail){
		Helper::logDeprecatedAccess();
		return true === self::isMailSyntaxOk($mail);
	}

	/**
	 * checks if the syntax of the given email adress is ok
	 * @param string $mail
	 * @return boolean
	 */
	public static function isMailSyntaxOk($mail){
		return false !== filter_var($mail, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * checks if the syntax of the given string is a valid url
	 * @param string $url
	 * @return boolean
	 */
	public static function isURLSyntaxOk($url){
		return false !== filter_var($url, FILTER_VALIDATE_URL);
	}

	/**
	 * converts the float point to comma and concats the euro sign
	 * @param string $value
	 * @return string
	 */
	public static function floatToDecimalPrice($value){
		return Helper::floatToDecimal($value).'€';
	}

	/**
	 * converts the float point to comma
	 * @param string $value
	 * @return number
	 */
	public static function floatToDecimal($value){
		return str_replace('.', ',', $value);
	}

	/**
	 * cuts a string
	 * if forceHardCut is not set to true, it takes the next letters until end of senctence(.,!,?) or end of word(' ',-,")
	 * @param string $text
	 * @param integer $maxLength
	 * @param string $suffix
	 * @param boolean $forceHardCut
	 * @return string
	 */
	public static function cutText($text, $maxLength = 50, $suffix = '...', $forceHardCut = false){
		if(strlen($text) < $maxLength){
			return $text;
		}

		if(null === $suffix){
			$suffix = '...';
		}
		if(true === $forceHardCut){
			if(strlen($suffix) > $maxLength){
				return substr($text, 0, $maxLength);
			}
			$text = substr($text, 0, $maxLength - strlen($suffix)).$suffix;
			return $text;
		}

		$return = substr($text, 0, $maxLength);
		$length = strlen($text);

		$endOfString = true;

		while($maxLength < $length){
			if(true === in_array($text[$maxLength], array(' ', '.', '!', '?', '-', '"', ','))){
				$endOfString = false;
				$return .= $text[$maxLength];
				break;
			}
			$return .= $text[$maxLength];
			$maxLength++;
		}
		if(false === $endOfString){
			$return .= $suffix;
		}

		return $return;
	}

	/**
	 *
	 * normalizes a string
	 * @param String $input
	 */
	public static function normalizeString($input){
		return lcfirst(str_replace(array('ä', 'ö', 'ü', 'ß'), array('ae', 'oe', 'ue', 'ss'), $input));
	}

	/**
	 * cleans a string for german only characters
	 * @param unknown_type $input
	 * @return mixed
	 */
	public static function cleanString($input){
		return str_replace(array('ä', 'ö', 'ü', ' ', 'ß'), array('ae', 'oe', 'ue', '_', 'ss'), $input);
	}

	/**
	 *
	 * default date format
	 * @var string
	 */
	public static $format = 'Y-m-d H:i:s';

	/**
	 * provides the path of the docroot with tailing slash
	 */
	public static function getDocRoot(){
		return realpath(__DIR__.'/../../').'/';
	}

	/**
	 *
	 * echoes a previously formatted given date
	 * @param string $date
	 */
	public static function renderDate($date){
		echo self::formatDate($date);
	}

	/**
	 *
	 * formats a date to d.m.Y H:i
	 * @param string $date
	 */
	public static function formatDate($date){
		return date('d.m.Y, H:i', self::dateToTimestamp($date));
	}

	/**
	 *
	 * returns a date with given format or the default format
	 * @param string $format
	 */
	public static function getDate($format = null, $time = null){
		if(null === $format){
			$format = self::$format;
		}
		if(null === $time){
			$time = time();
		}
		return date($format, $time);
	}

	/**
	 *
	 * returns a date for a timestamp
	 * @param string $format
	 * @param integer $time
	 */
	public static function getDateByTimestamp($format = null, $time = null){
		if(null === $time){
			$time = time();
		}
		if(null === $format){
			$format = self::$format;
		}
		return date($format, $time);
	}

	/**
	 * converts a string (2009-04-24 18:04:12) into an unix timestamp (1234567890)
	 * @param string $date
	 * @return string
	 */
	public static function dateToTimestamp($date){
		return strtotime($date);
	}

	/**
	 *
	 * makes an unix-timestamp from an utc date and formats it
	 * @param string $date
	 */
	public static function renderUTCDate($date){
		self::renderDate(date('Y-m-d H:i:s', strtotime($date)));
	}

	/**
	 *
	 * creates an utc timestamp
	 * @param string $date
	 */
	public static function createUTCDate($date){
		return gmdate('D, d M Y H:i:s +0000', strtotime($date));
	}

	/**
	 * displays the remaining time
	 * @param integer $seconds
	 * @return string
	 */
	public static function secondsToRemainingTime($seconds, $clearLeadingZeros = true){

		if(0 == $seconds){
			return 0;
		}
		$date = gmdate ('H:i:s', $seconds);
		if(false === $clearLeadingZeros){
			return $date;
		}
		$split = explode(':', $date);
		$offset = 0;
		while('00' === $split[$offset]){
			unset($split[$offset++]);
		}
		return implode(':', $split);
	}

	/**
	 *
	 * generates a passwort with length = $length
	 * takes all possible ascii numbers and creates characters of them
	 * every character is only one time in the password
	 * @param integer $length
	 * @return string
	 */
	public static function generatePassword($length = 9){
		$alphabet = array();
		foreach(range(97,122) as $ascii){
			$alphabet[] = chr($ascii);
			$alphabet[] = strtoupper(chr($ascii));
		}
		foreach(range(0,9) as $number){
			$alphabet[] = ''.$number;
		}
		foreach(array(range(33,64), range(91,96), range(123,126)) as $array){
			foreach($array as $ascii){
				$alphabet[] = chr($ascii);
			}
		}
		$pass = '';
		while(strlen($pass) < $length){
			$offset = rand(0, sizeof($alphabet) -1);
			$pass .= $alphabet[$offset];
			$alphabet = array_merge(array_slice($alphabet, 0, $offset), array_slice($alphabet, $offset +1 , sizeof($alphabet)));
		}
		return $pass;
	}

	/**
	 *
	 * splits an $array into $slots
	 * the first y items go to the first array, the z next to the second array, ...
	 * i call it the arrayToWurstMachineHellYeahBitch
	 * $slots indicates how many arrays should be returned back
	 *
	 * @param array $array
	 * @param integer $slots
	 */
	public static function array_split($array, $slots){
		return array_chunk($array, ceil(count($array) / $slots));
	}

	public static function array_decorate($array, $string = ''){
		foreach($array as $index => $current){
			if(true === is_array($current)){
				$array[$index] = Helper::array_decorate($current, $string);
				return;
			}
			if(true === is_string($current)){
				$array[$index] = $string.$current.$string;
			}
		}
		return $array;
	}

	/**
	 * computes the difference of two arrays recursivly
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @see http://www.php.net/manual/en/function.array-diff.php#91756
	 */
	public static function array_diff_recursive($aArray1, $aArray2){
		$aReturn = array();
		foreach($aArray1 as $mKey => $mValue){
			if(array_key_exists($mKey, $aArray2)){
				if(is_array($mValue)){
					$aRecursiveDiff = self::array_diff_recursive($mValue, $aArray2[$mKey]);
					if(count($aRecursiveDiff) > 0){
						$aReturn[$mKey] = $aRecursiveDiff;
					}
				}else{
					if($mValue != $aArray2[$mKey]){
						$aReturn[$mKey] = $mValue;
					}
				}
			}else{
				$aReturn[$mKey] = $mValue;
			}
		}
		return $aReturn;
	}

	/**
	 *
	 * logs an error message
	 * @param string $message
	 */
	public static function logerror($message){
		$trace = debug_backtrace();
		$trace = $trace[1];
		$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : 'CLI';
		$stack = Stack::getInstance();
		$user = ' | user: '.(true === Auth::isLoggedIn()? Auth::getUserNick() : 'arno nym');
		$url = ' | url: '.($_SERVER['REQUEST_URI']);
		$ref = isset($_SERVER['HTTP_REFERER'])? ' | ref: '.$_SERVER['HTTP_REFERER'] : '';
		$line = isset($trace['line'])? $trace['line'] : 666;
		$file = isset($trace['file'])? $trace['file'] : 'somewhere';
		$occurrenced = ' | file: '.(str_replace(Helper::getDocRoot(), '', $file)).' line '.$line;
		error_log($message.$user.$url.$ref.$occurrenced);
	}

	/**
	 *
	 * checks if filetype returns an image as content type
	 * $filename should be the fullpath
	 * @param string $filename
	 */
	public static function isImage($filename){
		return false !== strstr(self::getFileType($filename), 'image/');
	}

	/**
	 * returns the file name
	 * @param string $path
	 */
	public static function getFileName($path){
		return basename(realpath($path));
	}

	/**
	 *
	 * checks the mime content type of the filename
	 * $filename requires a fullpath
	 * @param string $filename
	 */
	public static function getFileType($filename){
		return mime_content_type($filename);
	}

	/**
	 * grabs the last chars from a file name
	 * @param string $filename
	 * @param boolean $prependPoint
	 */
	public static function getFileExtension($filename, $prependPoint = true){
		$path = explode('.', $filename);
		return strtolower((true === $prependPoint? '.': '').$path[sizeof($path)-1]);
	}

	/**
	 * scans a directory for files
	 * @param string $path
	 * @param boolean $recursive
	 * @param array $exclude
	 * @param boolean $filesOnly
	 * @return array
	 */
	public static function scanDirectory($path, $recursive = false, $exclude = array(), $filesOnly = true){
		if(false === is_dir($path)){
			Helper::logToFile('empty directory! can not scan '.$path, 'debug');
			return array();
		}
		$excludeDefault = array('.', '..');
		if(true === is_array($exclude)){
			$exclude = array_merge($excludeDefault, $exclude);
		}elseif(true === is_string($exclude)){
			$excludeDefault[] = $exclude;
			$exclude = $excludeDefault;
		}else{
			$exclude = $excludeDefault;
		}
		$exclude[] = '.svn';
		$return = array();

		$path = '/' === $path[strlen($path) - 1]? $path : $path.'/';

		if(false === $recursive){
			foreach (new DirectoryIterator($path) as $fileInfo) {
				if(true === $fileInfo->isDot() || $fileInfo->isDir()){
					continue;
				}
				$return[] = $path.$fileInfo->getFilename();
			}
		}else{
			$mode = true === $filesOnly? RecursiveIteratorIterator::LEAVES_ONLY : RecursiveIteratorIterator::SELF_FIRST;

			$files =  new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY);
			foreach(array_keys(iterator_to_array($files,true)) as $current){
				if(true === in_array(basename($current), $exclude)){
					continue;
				}
				$return[] = $current;
			}
		}

		return $return;
	}


	/**
	 * writes a string to a given logfile
	 * @param string $text
	 * @param string $dest
	 */
	public static function logToFile($text, $dest){
		if(false === is_dir('log')){
			mkdir('log', 0777, true);
		}
		$text .= "\n";
		$file = fopen(self::getDocRoot().'log/'.$dest, 'a+');
		fputs($file, $text, strlen($text));
		fclose($file);
	}

	/**
	 * converts filesize to the smallest unit (b,kb,mb,gb)
	 * @param integer $size
	 */
	public static function fileSize($size){
		if($size < 1024){
			return $size .' B';
		}
		if($size < 1024 * 1024){
			$size = ceil($size / 1024);
			return $size .' KB';
		}
		if($size < 1024 * 1024 * 1024){
			$size = ceil($size / (1024 * 1024));
			return $size .' MB';
		}
		if($size < 1024 * 1024 * 1024 * 1024){
			$size = ceil($size / (1024 * 1024 * 1024));
			return $size .' GB';
		}
		return $size;
	}

	/**
	 *
	 * puts a string into the splash-stack which is called from the view
	 * @param string $string
	 */
	public static function addSplash($string){
		$stack = Stack::getInstance();
		$splashes = $stack->get('splash');
		if(null === $splashes){
			$splashes = array();
		}
		$splashes[] = $string;
		$stack->set('splash', $splashes);
	}

	/**
	 *
	 * gets all splashes from the stack. clears all splashes in the stack. then calls the partial from the view loader.
	 */
	public static function getSplash(){
		$stack = Stack::getInstance();
		$splashes = $stack->get('splash');
		if(null === $splashes || true === empty($splashes)){
			return null;
		}
		self::clearSplashes();
		$load = Load::getInstance();
		$load->partial('layout/splash', $splashes, true);
	}

	/**
	 * returns the array that contains all splashes
	 */
	public static function getPlainSplashes(){
		$stack = Stack::getInstance();
		return $stack->get('splash');
	}


	/**
	 * deletes all splashes
	 * caution! can not be undone!
	 */
	public static function clearSplashes(){
		$stack = Stack::getInstance();
		$stack->set('splash', null);
	}

	/**
	 * creates a more understandable error message for file upload errnos
	 * @param integer $errno
	 * @return string
	 */
	public static function uploadErrorNumberToString($errno){
		$return = '';
		switch($errno){
			case UPLOAD_ERR_CANT_WRITE:{
				$return = 'Konnte Datei nicht schreiben.';
			}break;
			case UPLOAD_ERR_EXTENSION:{
				$return = 'Dateityp nicht akzeptiert.';
			}break;
			case UPLOAD_ERR_FORM_SIZE:{
				$return = 'Datei zu groß.';
			}break;
			case UPLOAD_ERR_INI_SIZE:{
				$return = 'Datei zu groß.';
			}break;
			case UPLOAD_ERR_NO_FILE:{
				$return = 'Keine Datei gesendet.';
			}break;
			case UPLOAD_ERR_NO_TMP_DIR:{
				$return = 'Kein Temp-Ordner gefunden.';
			}break;
			case UPLOAD_ERR_OK:{
				$return = 'Kein Fehler aufgetreten.';
			}break;
			case UPLOAD_ERR_PARTIAL:{
				$return = 'Unvollständiger Upload.';
			}break;
			default:{
				$return = 'Unbekannter Fehler. Mulder und Scully ermitteln schon!';
			}break;
		}
		return $return;
	}

	/**
	 * resizes a file
	 * caution! if the file is smaller, it will be forced to grow!!
	 * @param string $filepath
	 * @param integer $width
	 * @param integer $height
	 * @deprecated use resizeImage instead!
	 */
	public static function resize($filepath, $width = 600, $height = 600){
		Helper::logDeprecatedAccess('use resizeImage instead!');
		Helper::resizeImage($filepath, $width, $height);
	}

	/**
	 * resizes a file
	 * caution! if the file is smaller, it will be forced to grow!!
	 * @param string $filepath
	 * @param integer $width
	 * @param integer $height
	 */
	public static function resizeImage($filepath, $width = 600, $height = 600){
		passthru('mogrify -resize '.$width.'x'.$height.' '.$filepath);
	}

	/**
	 * creates a thumbnail for an image
	 * forces be $width x $height pixel
	 * it will be stretched down or up!
	 * @param string $source
	 * @param string $target
	 * @param integer $width
	 * @param integer $height
	 * @return boolean
	 */
	public static function createThumbnail($source, $target, $width = 200, $height = 200){
		$command = 'convert "'.$source.'" -antialias -resize '.$width.'x'.$height.'! "'.$target.'"';
		$return = $output = null;
		exec($command, $output, $return);
		return 0 === $return;
	}

	/**
	 * retrieves information about a youtube video
	 * provide just the plain youtube video id
	 * not https://www.youtube.com/watch?v=V9bwo4N1AAE => just V9bwo4N1AAE
	 * the object that is returned contains the title and the duration in seconds
	 * @param string $ytid
	 * @return stdClass
	 */
	public static function getYoutubeVideoInformation($ytid){
		$return = new stdClass();
		$url = 'http://gdata.youtube.com/feeds/api/videos?q='.$ytid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$feed = curl_exec($ch);
		curl_close($ch);
		$xml = simplexml_load_string($feed);
		if(true === in_array($xml, array(null, false), true)){
			throw new Exception('could not parse youtube information for ytid = '.$ytid);
		}
		$entry = $xml->entry[0];
		if(null === $entry){
			throw new Exception('could not parse youtube information for ytid = '.$ytid);
		}
		$media = $entry->children('media', true);
		$group = $media->group;
		$return->title = ucwords(strtolower($group->title));
		$content_attributes = $group->content->attributes();
		$return->duration = intval($content_attributes['duration'].'');
		return $return;
	}

	/**
	 * sends a mail..
	 * force = true forces sending the mail even if not in production mode
	 * the files array wants to have strings for the full file path
	 *
	 * @param string $sender
	 * @param string $reciever
	 * @param string $subject
	 * @param string $mailText
	 * @param array $files
	 * @param boolean $force
	 * @return boolean
	 */
	public static function sendMail($sender, $reciever, $subject, $mailText, $files = array(), $force = false){
		$stack = Stack::getInstance();
		$sending = false;
		if(true === $force || 'production' == $stack->get('mode')){
			$sending = true;
		}

		if(null === $sender){
			$sender = ADMIN_EMAIL;
		}

		$text = PHP_EOL.PHP_EOL.'___________________________'.PHP_EOL;
		if(false === $sending){
			$text .= 'DUMMY! NOT SENDING THIS!'.PHP_EOL;
		}
		$text .= 'date: '.self::getDate().PHP_EOL;
		$text .= 'reciever: '.$reciever.PHP_EOL;
		$text .= 'sender: '.$sender.PHP_EOL;
		$text .= 'subject: '.$subject.PHP_EOL;
		$text .= 'files: '.PHP_EOL;
		if(true === empty($files)){
			$text .= '-none-';
		}else{
			foreach($files as $current){
				$text .= $current.PHP_EOL;
			}
		}
		$text .= PHP_EOL;
		$text .= 'text: '.PHP_EOL.$mailText.PHP_EOL.PHP_EOL;
		$text .= PHP_EOL.'___________________________'.PHP_EOL;

		Helper::logToFile($text, 'maillog');

		if(false === $sending){
			return true;
		}
		//yes, really send this fucking email out to the nasty fucking shit reciever!
		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/plain; charset=UTF-8";
		$headers[] = "From: $sender";
		$headers[] = "Reply-To: $sender";
		$headers[] = "X-Mailer: PHP/".phpversion();
		$headers[] = "";

		return mail($reciever, '=?UTF-8?B?'.base64_encode($subject).'?=', $mailText, implode(PHP_EOL, $headers));
	}
}