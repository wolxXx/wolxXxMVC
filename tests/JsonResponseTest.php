<?
/**
 * @codeCoverageIgnore
 */
class JsonResponseTest extends  PHPUnit_Framework_TestCase{
	public function testJsonWithError(){
		$response = new JsonResponse();
		$response->setError(true);
		$response->setMessage('pew pew army');
		echo $response->toJSON();
		$this->expectOutputRegex('/"status":"0"/');
		$this->expectOutputRegex('/"error":"true"/');
		$this->expectOutputRegex('/"data":\[\]/');
		$this->expectOutputRegex('/"message":"pew pew army"/');
	}

	public function testRebuildFromJson(){
		$response = new JsonResponse();
		$response->setError(true);
		$response->setMessage('pew pew army');
		$json = $response->toJSON();
		$rebuild = json_decode($json);
		$rebuild->error = 'true' === $rebuild->error;
		$this->assertTrue($rebuild->error);
		$this->assertSame($rebuild->message, 'pew pew army');
	}

	public function testDefaultJsonObject(){
		$response = new JsonResponse();
		$json = $response->toJSON();
		$rebuild = json_decode($json);
		$rebuild->error = 'true' === $rebuild->error;
		$this->assertFalse($rebuild->error);
	}

	public function testAddData(){
		$response = new JsonResponse();
		$response->addData('foo', 'baar');
		$json = $response->toJSON();
		echo $response->toJSON();
		$this->expectOutputRegex('/"status":"0"/');
		$this->expectOutputRegex('/"data":{"foo":"baar"}/');
	}

	public function testAddAndClearData(){
		$response = new JsonResponse();
		$response->addData('foo', 'baar');
		$response->clearData();
		echo $response->toJSON();
		$this->expectOutputRegex('/"status":"0"/');
		$this->expectOutputRegex('/"data":\[\]/');
	}

	public function testSetAndClearMessage(){
		$response = new JsonResponse();
		$response->setMessage('huhu');
		$response->clearMessage();
		echo $response->toJSON();
		$this->expectOutputRegex('/"message":""/');
		$this->expectOutputRegex('/"status":"0"/');
		$this->expectOutputRegex('/"data":\[\]/');
	}

	public function testSetStatusCode(){
		$response = new JsonResponse();
		$response->setStatus(403);
		echo $response->toJSON();
		$this->expectOutputRegex('/"message":""/');
		$this->expectOutputRegex('/"status":"403"/');
		$this->expectOutputRegex('/"data":\[\]/');
	}
}