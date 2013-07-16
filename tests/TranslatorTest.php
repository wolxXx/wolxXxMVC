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
<<<<<<< HEAD
	 * @expectedException TranslatorException
	 */
	public function testMissingParams(){
=======
<<<<<<< HEAD
	 * @expectedException TranslatorException
	 */
	public function testMissingParams(){
=======
	 * @expectedException Exception
	 */
	public function testMissingParams(){

>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
		$this->assertSame('foo bar 42', Translator::translate('foo %s %s', 'bar'));
	}
}