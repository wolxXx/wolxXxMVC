<?

class FileLogger implements LoggerInterface{
	
	protected $file;
	
	public function __construct($file){
		$this->file = $file;		
	}
	
	public function log($message){
		$text = Helper::getDate().' | '.$message;
		Helper::logToFile($text, $this->file);
	}
}