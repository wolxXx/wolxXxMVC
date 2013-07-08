<?
/**
 * @codeCoverageIgnore
 */
class HTMLTest extends  PHPUnit_Framework_TestCase{
	function testFactory(){
		$form = HTML::Factory('Form');
		$this->assertTrue($form instanceof Form);
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
			->setNameAndId('myform')
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
}