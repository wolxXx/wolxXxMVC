<?
/**
 * @codeCoverageIgnore
 */
class TranslatorTest extends  PHPUnit_Framework_TestCase{
	public function testNoParam(){
		$this->assertSame('foo', Translator::translate('foo'));
	}

	public function testOneParam(){
		$this->assertSame('foo bar', Translator::translate('foo %s', 'bar'));
	}

	public function testTwoParams(){
		$this->assertSame('foo bar 42', Translator::translate('foo %s %s', 'bar', 42));
	}
	 /**
	 * @expectedException TranslatorException
	 */
	public function testMissingParams(){
		$this->assertSame('foo bar 42', Translator::translate('foo %s %s', 'bar'));
	}
}