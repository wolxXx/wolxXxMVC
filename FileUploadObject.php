<?
/**
 * a wrapper for sent files
 * so the uploaded file is available as object
 * makes everything more comfortable
 *
 * @version 1.0
 * @author wolxXx
 * @package wolxXxMVC
 *
 */
class FileUploadObject{
	/**
	 * temporary file path
	 * $tmp_name in array
	 * @var string
	 */
	public $tempName;

	/**
	 * the error number
	 * @var integer
	 */
	public $errorNumber;

	/**
	 * the error message
	 * @var string
	 */
	public $errorMessage;

	/**
	 * file type
	 * @var string
	 */
	public $type;

	/**
	 * the file extension
	 * @var string
	 */
	public $extension;

	/**
	 * indicator if the uploaded file is an image
	 * @var boolean
	 */
	public $isImage;

	/**
	 * the file size in byte
	 * @var integer
	 */
	public $size;


	/**
	 * human readable file size
	 * @var string
	 */
	public $sizeText;

	/**
	 * the file name
	 * @var string
	 */
	public $name;

	/**
	 * obfuscated, hashed file name
	 * so duplicated filenames are not occuring
	 * @var string
	 */
	public $newFileName;

	/**
	 * the index in the $_FILES array
	 * because maybe useful later
	 * @var string
	 */
	public $uploadIndex;

	/**
	 * if there was an error at upload
	 * @var boolean
	 */
	public $uploadSuccessful;

	/**
	 * constructor needs the usual file upload infos
	 * sets the is image flag
	 * grabs the extension
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $tmp_name
	 * @param integer $error
	 * @param integer $size
	 * @param string $uploadIndex
	 */
	public function __construct($name, $type, $tmp_name, $error, $size, $uploadIndex){
		$this
			->setName($name)
			->setType($type)
			->setTempName($tmp_name)
			->setErrorNumber($error)
			->setFileSize($size)
			->setUploadIndex($uploadIndex)
			->setIsImage()
			->setExtension()
			->setErrorMessage()
			->setSizeText()
			->setUploadSuccessful()
			->setNewFileName();
	}

	/**
	 * setter for the upload index
	 * @param string $index
	 * @return FileUploadObject
	 */
	public function setUploadIndex($index){
		$this->uploadIndex = $index;
		return $this;
	}

	/**
	 * setter for the file size
	 * @param integer $size
	 * @return FileUploadObject
	 */
	public function setFileSize($size){
		$this->size = $size;
		return $this;
	}

	/**
	 * setter for the error number
	 * @param integer $errorNumber
	 * @return FileUploadObject
	 */
	public function setErrorNumber($errorNumber){
		$this->errorNumber = $errorNumber;
		return $this;
	}

	/**
	 * setter for the name
	 * @param string $name
	 * @return FileUploadObject
	 */
	public function setName($name){
		$this->name = $name;
		return $this;
	}

	/**
	 * setter for the file type
	 * @param string $type
	 * @return FileUploadObject
	 */
	public function setType($type){
		$this->type = $type;
		return $this;
	}

	/**
	 * setter for the temp name
	 * @param string $tempName
	 * @return FileUploadObject
	 */
	public function setTempName($tempName){
		$this->tempName = $tempName;
		return $this;
	}

	/**
	 * creates a new file name hashed with time and name
	 * makes sure that there will be no name conflicts
	 * @return FileUploadObject
	 */
	public function setNewFileName(){
		$this->newFileName = md5(time().$this->name.$this->sizeText.$this->size.$this->type).$this->extension;
		return $this;
	}

	/**
	 * getter for the new file name
	 * @return string
	 */
	public function getNewFileName(){
		return $this->newFileName;
	}

	/**
	 * checks if the errorNumber is zero
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 * @return FileUploadObject
	 */
	public function setUploadSuccessful(){
		$this->uploadSuccessful = 0 === $this->errorNumber;
		return $this;
	}

	/**
	 * checks if the file is an image
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 * @return FileUploadObject
	 */
	public function setIsImage(){
		$this->isImage = Helper::isImage($this->tempName);
		return $this;
	}

	/**
	 * grabs the file extension from the filename
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 * @return FileUploadObject
	 */
	public function setExtension(){
		$this->extension = Helper::getFileExtension($this->name);
		return $this;
	}

	/**
	 * set the error message for the error number
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 * @return FileUploadObject
	 */
	public function setErrorMessage(){
		$this->errorMessage = Helper::uploadErrorNumberToString($this->errorNumber);
		return $this;
	}

	/**
	 * sets the size test for the bytes
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 * @return FileUploadObject
	 */
	public function setSizeText(){
		$this->sizeText = Helper::fileSize($this->size);
		return $this;
	}

	/**
	 * moves the file to a target
	 * @param string $target
	 * @return boolean
	 */
	public function move($target){
		if(false === is_dir(dirname(Helper::getDocRoot().$target))){
			mkdir(dirname(Helper::getDocRoot().$target), 0777, true);
		}
		return rename($this->tempName, $target);
	}
}