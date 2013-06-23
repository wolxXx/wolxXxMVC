<?

class EmailLogger implements LoggerInterface{
	protected $reciever;
	protected $sender;
	
	public function __construct($reciever, $sender){
		$this->reciever = $reciever;
		$this->sender = $sender;
	}
	
	public function log($message){
		Helper::sendMail2(array(
			'mailto' => ADMIN_EMAIL,
			'subject' => 'mail logger',
			'text' => $message
		));
	}
}