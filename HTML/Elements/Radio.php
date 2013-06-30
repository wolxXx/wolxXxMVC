<?
class Radio extends ContainableDomElementAbstract{
	public static function getDefaultConf(){
		return array(
		);
	}

	public function addChild(DomElementInterface $child){
		if($child instanceof RadioOption){
			$child->setName($this->get('name'));
			parent::addChild($child);
			return $this;
		}
		throw new Exception('radio elements can only contain radio options as children');
	}

	public function display(){
		foreach($this->children as $current){
			$current->display();
		}
		return $this;
	}
}