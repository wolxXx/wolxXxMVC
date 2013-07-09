<?
/**
 * request class
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @version 1.1
 */
class Request{
	/**
	 * keys in the post data that is ignored in log method
	 *
	 * @var array
	 */
	protected $ignoredPostForLog = array('pass', 'password', 'passwort');

	/**
	 * flag if the request was made by an ajax call
	 *
	 * @var boolean
	 */
	protected $isAjax;

	/**
	 * flag if the request has post data
	 *
	 * @var boolean
	 */
	protected $isPost;

	/**
	 * flag if the request was made by a mobile device
	 * or the application environment is set to mobile
	 *
	 * @var boolean
	 */
	protected $isMobile;

	/**
	 * the url path
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * an instance of a data object
	 *
	 * @var DataObject
	 */
	public $dataObject;

	/**
	 * an instance of the stack
	 *
	 * @var Stack
	 */
	protected $stack;

	/**
	 * constructor
	 * initializes via init method
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * grabs the instance of the stack
	 * creates a new data object instance
	 * checks if request is ajax, post and mobile
	 */
	protected function init(){
		$this->stack = Stack::getInstance();
		$this->dataObject = new DataObject();
		$this->checkIfRequestIsAjax();
		$this->checkIfRequestIsPost();
		$this->checkIfRequestIsMobile();
	}

	/**
	 * checks if the request was made via ajax
	 *
	 * @return Request
	 */
	protected function checkIfRequestIsAjax(){
		$this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
		return $this;
	}

	/**
	 * checks if the request contains post data
	 *
	 * @return Request
	 */
	protected function checkIfRequestIsPost(){
		$post = $this->dataObject->getRawPOST();
		$this->isPost = false === empty($post);
		return $this;
	}

	/**
	 * checks if the request was made from a mobile device
	 * or the application environment was set to mobile
	 *
	 * @return Request
	 */
	protected function checkIfRequestIsMobile(){
		$detect = new Mobile_Detect();
		if($detect->isMobile() || 'mobile' === $this->stack->get('version')){
			$this->isMobile = true;
		}else{
			$this->isMobile = false;
		}
		return $this;
	}

	/**
	 * determines if the request is an ajax request
	 *
	 * @return boolean
	 */
	public function isAjax(){
		return $this->isAjax;
	}

	/**
	 * determines if the request is post request
	 *
	 * @return boolean
	 */
	public function isPost(){
		return $this->isPost;
	}

	/**
	 * determines if the request is mobile
	 *
	 * @return boolean
	 */
	public function isMobile(){
		return $this->isMobile;
	}

	/**
	 * logs the post data
	 * fields named "pass", "password" or "passwort" are replaced with ****
	 *
	 * @param string $destination
	 * @return Request
	 */
	public function postLog($destination){
		if(false === $this->isPost()){
			return $this;
		}
		$delim = ' | ';
		$txt = Helper::getDate().$delim;
		$txt .= Helper::getUserIP().$delim;
		$txt .= true === Auth::isLoggedIn()? 'true ('.Auth::getUserNick().')'.$delim : 'false'.$delim;
		$txt .= Helper::getCurrentURI().$delim;
		$txt .= "\nvalues:\n";
		foreach($this->dataObject->getRawPOST() as $key => $value){
			if(in_array($key, $this->ignoredPostForLog)){
				$value = '****';
			}
			if(in_array($key, array('base64data'))){
				$value = '[base64data length: '.strlen($value).']';
			}
			if(true === is_array($value)){
				$newvalue = '';
				foreach($value as $x => $y){
					$newvalue .= "$x = $y, ";
				}
				$value = trim($newvalue);
			}
			$txt .= "$key: $value\n";
		}
		$txt .= "________________\n";
		Helper::logToFile($txt, $destination);
		return $this;
	}
}