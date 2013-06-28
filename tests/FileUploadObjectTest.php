<?
/**
 * @codeCoverageIgnore
 */
class FileUploadObjectTest extends  PHPUnit_Framework_TestCase{
	public function testFileUploadError(){
		$file = new FileUploadObject('header.png', 'image/png', __DIR__.DIRECTORY_SEPARATOR.'_src/header.png', UPLOAD_ERR_OK, 1234, '');
		$this->assertTrue($file->isImage);
		$this->assertSame(Helper::getFileExtension($file->getNewFileName()), '.png');
		$this->assertSame(Helper::fileSize($file->size), '2 KB');
		$this->assertSame('Kein Fehler aufgetreten.', $file->errorMessage);
		$this->assertSame(0, $file->errorNumber);
	}
}