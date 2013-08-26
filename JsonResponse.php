<?
/**
 * default and standard json response
 *
 * @author wolxXx
 * @version 1.4
 * @package wolxXxMVC
 */
class JsonResponse{
	/**
	 * http like status code
	 *
	 * @var integer
	 */
	 protected $status;

	/**
	 * if there was an error
	 *
	 * @var boolean
	 */
	protected $error;

	/**
	 * the transport data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * optional message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * constructor
	 * sets default values:
	 * 	error = false
	 * 	status = ok
	 * 	message = apiStatusText for ok
	 * 	data = empty array
	 */
	public function __construct(){
		$this
			->setError(false)
			->setMessage(ApiStatus::getStatusTextForCode(ApiStatus::$success))
			->setStatus(ApiStatus::$success)
			->clearData()
		;
	}

	/**
	 * echoes the the generated json
	 */
	public function outputJSON(){
		echo $this->toJSON();
	}

	/**
	 * converts the class to a json object
	 *
	 * @return string
	 */
	public function toJSON(){
		$json = new stdClass();
		foreach(array(
			'message',
			'status',
			'error',
			'data'
		) as $foo){
			$json->$foo = $this->$foo;
		}
		return json_encode($json);
	}

	/**
	 * sets the whole data array
	 *
	 * @param array $data
	 * @return JsonResponse
	 */
	public function setData($data = array()){
		$this->data = $data;
		return $this;
	}

	/**
	 * clears all data
	 *
	 * @return JsonResponse
	 */
	public function clearData(){
		$this->setData();
		return $this;
	}

	/**
	 * adds data to the data array
	 *
	 * @param string $key
	 * @param something $value
	 * @return JsonResponse
	 */
	public function addData($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * setter for the error status
	 *
	 * @param boolean $value
	 * @return JsonResponse
	 */
	public function setError($value){
		$this->error = true === $value? "true" : "false";
		return $this;
	}

	/**
	 * setter for the status code
	 * automatically sets the message with the status code string
	 *
	 * @param integer $status
	 * @return JsonResponse
	 */
	public function setStatusAndBelongingMessage($status){
		$this->setStatus($status);
		$this->setMessage(ApiStatus::getStatusTextForCode($status));
		return $this;
	}

	/**
	 * sets the error flag to true
	 * sets the status and its message
	 *
	 * @param integer $status
	 * @return JsonResponse
	 */
	public function setStatusAndBelongingMessageForError($status){
		$this->setError(true);
		return $this->setStatusAndBelongingMessage($status);
	}

	/**
	 * setter for the status code
	 *
	 * @param integer $value
	 * @return JsonResponse
	 */
	public function setStatus($value){
		$this->status = intval($value)."";
		return $this;
	}

	/**
	 * setter for the message field
	 *
	 * @param string $value
	 * @return JsonResponse
	 */
	public function setMessage($value){
		$this->message = $value;
		return $this;
	}

	/**
	 * clears the message
	 *
	 * @return JsonResponse
	 */
	public function clearMessage(){
		return $this->setMessage('');
	}

	/**
	 * getter for the error flag
	 *
	 * @return boolean
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * getter for the message string
	 *
	 * @return string
	 */
	public function getMessage(){
		return $this->message;
	}

	/**
	 * getter for the status code
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * getter for the data
	 *
	 * @return array | stdClass
	 */
	public function getData(){
		return $this->data;
	}
}