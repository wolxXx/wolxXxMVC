<?
class Headline extends DomElementAbstract{
	public static function getDefaultConf(){
		return array(
			'size' => 1,
			'text' => ''
		);
	}

	public function setSize($size){
		$this->set('size', $size);
		return $this;
	}

	public function setText($text){
		$this->set('text', $text);
	}

	public function display(){
		HTML::renderHeadline($this->data->getData());
		return $this;
	}
}