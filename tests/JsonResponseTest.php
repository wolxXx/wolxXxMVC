<?
/**
 * @codeCoverageIgnore
 */
class JsonResponseTest extends  PHPUnit_Framework_TestCase{
	public function testJsonWithError(){
		$response = new JsonResponse();
		$response->setError(true);
		$response->setMessage('pew pew army');
		$json = $response->toJSON();
		$this->assertSame('{"status":200,"error":true,"data":[],"message":"pew pew army"}', $json);
	}

	public function testRebuildFromJson(){
		$response = new JsonResponse();
		$response->setError(true);
		$response->setMessage('pew pew army');
		$json = $response->toJSON();
		$rebuild = json_decode($json);
		$this->assertTrue($rebuild->error);
	}

	public function testDefaultJsonObject(){
		$response = new JsonResponse();
		$json = $response->toJSON();
		$rebuild = json_decode($json);
		$this->assertFalse($rebuild->error);
	}

	public function testAddData(){
		$response = new JsonResponse();
		$response->addData('foo', 'baar');
		$json = $response->toJSON();
		$this->assertSame('{"status":200,"error":false,"data":{"foo":"baar"},"message":""}', $json);
	}

	public function testAddAndClearData(){
		$response = new JsonResponse();
		$response->addData('foo', 'baar');
		$response->clearData();
		$json = $response->toJSON();
		$this->assertSame('{"status":200,"error":false,"data":[],"message":""}', $json);
	}

	public function testSetAndClearMessage(){
		$response = new JsonResponse();
		$response->setMessage('huhu');
		$response->clearMessage();
		$json = $response->toJSON();
		$this->assertSame('{"status":200,"error":false,"data":[],"message":""}', $json);
	}

	public function testSetStatusCode(){
		$response = new JsonResponse();
		$response->setStatus(403);
		$json = $response->toJSON();
		$this->assertSame('{"status":403,"error":false,"data":[],"message":""}', $json);
	}
}