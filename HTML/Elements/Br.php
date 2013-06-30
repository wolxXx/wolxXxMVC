<?
class Br extends DomElementAbstract{
	public static function getDefaultConf(){
		return array(
		);
	}

	public function display(){
		HTML::renderBr();
	}
}