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
		$this->expectOutputString('<input type="submit" id="pewpew" value="pewpew" />');
		HTML::Factory('Submit')->setId('pewpew')->setValue('pewpew')->display();
	}

	function testRenderPassword(){
		$this->expectOutputString('<input id="pewpew" name="password" type="password" />');
		HTML::Factory('Password')->setId('pewpew')->setName('password')->display();
	}

	function testFactoryInitWithArgs(){
		$this->expectOutputString('<input type="text" id="pewpew" name="password" />');
		HTML::Factory('Input', array(
			'id' => 'pewpew',
			'name' => 'password'
		))->display();
	}

	function testRenderInput(){
		$this->expectOutputString('<input type="text" id="pewpew" name="password" />');
		HTML::Factory('Input')->setId('pewpew')->setName('password')->display();
	}

	public function testFormGeneration(){
		$this->expectOutputString('<form method="post" action="/auth/login" id="myform"><label for="password">password</label><input id="password" name="password" type="password" /><label for="email">email</label><input type="text" id="email" name="email" /><input type="submit" id="submit" value="Submit" /></form>');
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
		$this->expectOutputString('<textarea id="pewpew" name="password" rows="1000" cols="1000">test</textarea>');
		HTML::Factory('Textarea')->setId('pewpew')->setName('password')->setText('test')->display();
	}
}