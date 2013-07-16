<?
/**
 * @codeCoverageIgnore
 */
class HTMLTest extends  PHPUnit_Framework_TestCase{
	public function testBreak(){
		$this->expectOutputString(PHP_EOL.'<br />'.PHP_EOL);
		HTML::Factory('Br')->display();
	}

	public function testButton(){
		$this->expectOutputRegex('/moo!/');
		$this->expectOutputRegex('/<\/button>/');
		$this->expectOutputRegex('/<button/');
		HTML::Factory('Button')
			->setText('moo!')
			->display()
		;
	}

	public function testCheckbox(){
		$this->expectOutputRegex('/value="miau"/');
		$this->expectOutputRegex('/checked="checked"/');
		HTML::Factory('Checkbox')
			->setIsChecked(true)
			->setNameAndId('foo')
			->setValue('miau')
			->display()
		;
	}

	public function testClearElem(){
		$this->expectOutputRegex('/class="clear"/');
		$this->expectOutputRegex('/<div/');
		HTML::Factory('Clear')->display();
	}

	/**
	 * @expectedException Exception
	 */
	public function testDropdownAddNonRadioElem(){
		$group = HTML::Factory('Dropdown');
		$group->addChild(HTML::Factory('Submit'));
	}

	public function testDropdown(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/selected="select">/');
		$this->expectOutputRegex('/bar/');
		HTML::Factory('Dropdown')
			->addChild(
				HTML::Factory('DropdownGroup')
					->addLabel('foo')
					->addChild(
						HTML::Factory('DropdownElement')
							->setValueAndText('bar')
							->setIsSelected(true)
					)
			)
			->addChild(
				HTML::Factory('DropdownElement')
				->setValueAndText('pewpew')
			)
			->display()
		;
	}

	public function testSelectedDropdown(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/selected="select">/');
		$this->expectOutputRegex('/bar/');
		HTML::Factory('DropdownElement')
			->setNameAndId('foo')
			->setValueAndText('bar')
			->setIsSelected(true)
			->display()
		;
	}

	public function testDropdownGroup(){
		$this->expectOutputRegex('/value="foo">/');
		$this->expectOutputRegex('/value="bar">/');
		$this->expectOutputRegex('/ahoibrause/');
		$group = HTML::Factory('DropdownGroup');
		$group->addLabel('ahoibrause mit vodka!');
		$group->addChild(HTML::Factory('DropdownElement')->setValueAndText('foo'));
		$group->addChild(HTML::Factory('DropdownElement')->setValueAndText('bar'));
		$group->display();
	}

	/**
	 * @expectedException Exception
	 */
	public function testDropdownGroupAddNonRadioElem(){
		$group = HTML::Factory('DropdownGroup');
		$group->addChild(HTML::Factory('Submit'));
	}

	/**
	 * @expectedException Exception
	 */
	public function testRemoveProperty(){
		$form = HTML::Factory('Form');
		$form->setData(array('foo' => 'bar'));
		$this->assertSame('bar', $form->get('foo'));
		$form->removeProperty('foo')->get('foo');
	}

	public function testSetData(){
		$this->assertSame('bar', HTML::Factory('Form')->setData(array('foo' => 'bar'))->get('foo'));
	}

	public function testAddData(){
		$data = array('foo' => 'bar');
		$this->assertSame($data, HTML::Factory('Form')->clearData()->addData($data)->getData());
		$this->assertSame('bar', HTML::Factory('Form')->addData($data)->get('foo'));
	}

	public function testAddClass(){
		$this->expectOutputRegex('/class="foo bar"/');
		HTML::Factory('Form')->addClass('foo')->addClass('bar')->display();
	}

	public function testSetClass(){
		$this->expectOutputRegex('/class="foo"/');
		HTML::Factory('Form')->setClass('foo')->display();
	}

	public function testLabelAfter(){
		$this->expectOutputRegex('/foo/');
		HTML::Factory('Password')->addLabel('foo', 'after')->display();

	}

	public function testGetId(){
		$this->assertSame('foo', HTML::Factory('Form')->setNameAndId('foo')->getId());
	}

	public function testUploadFormGeneration(){
		$this->expectOutputRegex('/method="post"/');
		$this->expectOutputRegex('/enctype="multipart\/form-data"/');
		HTML::Factory('Form')
		->setIsUploadForm(true)
		->display()
		;
	}

	function testGrid(){
		$this->expectOutputRegex('/<div class="grid_4">/');
		$this->expectOutputRegex('/<div class="clear">/');
		HTML::Factory('Grid')
			->setSize(4)
			->addChild(
				HTML::Factory('span')
					->setText('moo')
			)
			->display()
			->clear()
		;
	}

	function testHeadline(){
		$this->expectOutputRegex('/<h3/');
		$this->expectOutputRegex('/ahoibrause/');
		$this->expectOutputRegex('/\/h3>/');
		HTML::Factory('headline')->setSize(3)->setText('ahoibrause')->display();
	}

	function testInputType(){
		$this->expectOutputRegex('/type="moo"/');
		HTML::Factory('Input')->setType('moo')->display();
	}

	function testLabel(){
		$this->expectOutputRegex('/foo/');
		$this->expectOutputRegex('/for="moo"/');
		$label = HTML::Factory('Label');
		$label->setText('foo');
		$label->setPosition('foo');
		$this->assertSame('after', $label->getPosition());
		$label->setPosition('before');
		$this->assertSame('before', $label->getPosition());
		$label->setPosition();
		$this->assertSame('after', $label->getPosition());
		$label->setLabel(HTML::Factory('Label'));
		$label->addLabel('foo');
		$label->setFor('moo');
		$label->display();
	}

	function testPlaintext(){
		$this->expectOutputRegex('/foo/');
		HTML::Factory('Plaintext')->addText('foo')->addText('foo')->setText('foo')->display();
	}

	/**
	 * @expectedException Exception
	 */
	function testAddNonRadioOptionToRadioElem(){
		HTML::Factory('Radio')->addChild(HTML::Factory('Submit'));
	}

	function testRadioElem(){
		$radio = HTML::Factory('Radio');
		$this->expectOutputRegex('/value="foo!"/');
		$this->expectOutputRegex('/value="bar!"/');
		$this->expectOutputRegex('/name="'.$radio->getName().'"/');
		$radio->addChild(HTML::Factory('RadioOption')->setValue('foo!'));
		$radio->addChild(HTML::Factory('RadioOption')->setValue('bar!'));
		$this->assertSame(2, sizeof($radio->getChildren()));
		$radio->display();
	}

	function testRenderRadioOption(){
		$this->expectOutputRegex('/\<input/');
		$this->expectOutputRegex('/id="ahoibrause"/');
		$this->expectOutputRegex('/name="ahoibrause"/');
		$this->expectOutputRegex('/value="foo!"/');
		$this->expectOutputRegex('/type="radio"/');
		$this->expectOutputRegex('/\/>/');
		HTML::Factory('RadioOption')->setNameAndId('ahoibrause')->setValue('foo!')->display();
	}

	function testFactory(){
		$elems = array(
			'Br',
			'Button',
			'Checkbox',
			'Clear',
			'Dropdown',
			'DropdownElement',
			'DropdownGroup',
			'Form',
			'Grid',
			'Headline',
			'Input',
			'Label',
			'Password',
			'Plaintext',
			'Radio',
			'RadioOption',
			'Span',
			'Submit',
			'Textarea',
		);
		foreach($elems as $elem){
			$object = HTML::Factory($elem);
			$this->assertTrue($object instanceof $elem);
		}
	}

	function testAddChild(){
		$form = HTML::Factory('Form');
		$form->addChild(HTML::Factory('Submit'));
		$children = $form->getChildren();
		$this->assertSame(1, sizeof($children));
		$this->assertTrue($children[0] instanceof Submit);
	}

	function testAddChildren(){
		$amount = 5;
		$form = HTML::Factory('Form');
		$children = array();
		foreach(range(1, $amount) as $counter){
			$children[] = HTML::Factory('Submit');
		}
		$form->addChildren($children);
		$this->assertSame($amount, sizeof($form->getChildren()));
		foreach($form->getChildren() as $current){
			$this->assertTrue($current instanceof Submit);
		}
	}

	/**
	 * @expectedException Exception
	 */
	function testFailingFactory(){
		$form = HTML::Factory('UpdateObject');
	}

	function testRenderSubmit(){
		HTML::Factory('Submit')->setId('pewpew')->setValue('pewpew')->display();
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/type="submit"/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/value="pewpew"/');
		$this->expectOutputRegex('/\/>/');
	}

	function testRenderPassword(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="password"/');
		$this->expectOutputRegex('/ \/>/');
		HTML::Factory('Password')->setId('pewpew')->setName('password')->display();
	}

	function testFactoryInitWithArgs(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="text"/');
		$this->expectOutputRegex('/ \/>/');
		HTML::Factory('Input', array(
			'id' => 'pewpew',
			'name' => 'password'
		))->display();
	}

	function testRenderInput(){
		$this->expectOutputRegex('/<input/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/type="text"/');
		$this->expectOutputRegex('/ \/>/');
		HTML::Factory('Input')->setId('pewpew')->setName('password')->display();
	}

	public function testFormGeneration(){
		$this->expectOutputRegex('/<form/');
		$this->expectOutputRegex('/method="post"/');
		$this->expectOutputRegex('/action="\/auth\/login"/');
		$this->expectOutputRegex('/id="myform"/');
		$this->expectOutputRegex('/<label/');
		$this->expectOutputRegex('/for="password"/');
		$this->expectOutputRegex('/password/');
		$this->expectOutputRegex('/<\/label>/');
		$this->expectOutputRegex('/<input /');
		$this->expectOutputRegex('/label for="email"/');
		$this->expectOutputRegex('/name="email"/');
		$this->expectOutputRegex('/value="Submit"/');
		$this->expectOutputRegex('/<\/form>/');
		HTML::Factory('Form')
			->setAction('/auth/login')
			->setMethod('post')
			->setNameAndId('myform')
			->setIsUploadForm(false)
			->addChild(
				HTML::Factory('Password')
					->setNameAndId('password')
					->setLabel(
						HTML::Factory('Label', array(
							'id' => null,
							'text' => 'password',
							'for' => 'password'
						))
					)
			)
			->addChild(
				HTML::Factory('Input')
					->setNameAndId('email')
					->setLabel(
						HTML::Factory('Label', array(
							'id' => null,
							'text' => 'email',
							'for' => 'email'
						))
					)
					->setValue(true === DataObject::getInstance()->hasDataForKey('email')? DataObject::getInstance()->get('email'): null)
			)
			->addChild(
				HTML::Factory('Submit')
					->setValue('Submit')
					->setId('submit')
			)
			->display()
		;
	}

	function testRenderTextarea(){
		$this->expectOutputRegex('/\<textarea/');
		$this->expectOutputRegex('/id="pewpew"/');
		$this->expectOutputRegex('/name="password"/');
		$this->expectOutputRegex('/rows="1000"/');
		$this->expectOutputRegex('/cols="1000"/');
		$this->expectOutputRegex('/>test'.PHP_EOL.'</');
		$this->expectOutputRegex('/<\/textarea>/');
		HTML::Factory('Textarea')->setId('pewpew')->setName('password')->setText('test')->display();
	}

	function testSetRowsAndColumsForTextArea(){
		$this->expectOutputRegex('/rows="50"/');
		$this->expectOutputRegex('/cols="100"/');
		HTML::Factory('Textarea')->setRows(50)->setCols(100)->display();
	}

	function testRenderSpan(){
		$this->expectOutputRegex('/\<span/');
		$this->expectOutputRegex('/id="ahoibrause"/');
		$this->expectOutputRegex('/name="ahoibrause"/');
		$this->expectOutputRegex('/>foo!'.PHP_EOL.'</');
		$this->expectOutputRegex('/<\/span>/');
		HTML::Factory('span')->setNameAndId('ahoibrause')->setText('foo!')->display();
	}
}