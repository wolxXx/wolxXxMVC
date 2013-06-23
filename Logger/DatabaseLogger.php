<?

class DatabaseLogger implements LoggerInterface{
	
	private $connection;
	private $table;
	
	public function __construct($connection, $table){
		$this->connection = $connection;
		$this->table = $table;
	}
	
	public function log($message){
		$save = new SaveObject($this->table);
		$save->created = Helper::getDate();
		$save->message = $message;
		$save->save();
	}
}