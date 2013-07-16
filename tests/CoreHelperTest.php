<?
/**
 * @codeCoverageIgnore
 */
class CoreHelperTest extends  PHPUnit_Framework_TestCase{
	protected static $timestamp = 1343146996;

	public function testMultiArrayDecorate(){
		$array = array('foo', array('foo'));
		$array = Helper::array_decorate($array, '_');
		$this->assertSame('_foo_', $array[0]);
		$this->assertSame('_foo_', $array[1][0]);
	}

	public function testScanDirWithNotProvidingDir(){
		$this->assertSame(array(), Helper::scanDirectory(null));
	}

	public function testArrayDiffRecursiveNoDiffs(){
		$array1 = array(
			range(0,10),
			range(0,100),
		);
		$array2 = array(
			range(0,10),
			range(0,100)
		);
		$this->assertSame(array(), Helper::array_diff_recursive($array1, $array2));
	}

	public function testArrayDiffRecursive(){
		$array1 = array(
			range(0,10),
			range(0,100),
		);
		$array2 = array(
			range(0,9),
			range(0,99)
		);
		$this->assertSame(array(0 => array(10 => 10), 1 => array(100 => 100)), Helper::array_diff_recursive($array1, $array2));
	}

	public function testDecorateArray(){
		$array = array('foo');
		$array = Helper::array_decorate($array, '_');
		$this->assertSame('_foo_', $array[0]);
	}

	public function testSecondsToRemaining(){
		$this->assertSame(0, Helper::secondsToRemainingTime(0));
	}

	public function testIsUrlSyntaxNotOk(){
		$this->assertFalse(Helper::isURLSyntaxOk('asd'));
	}

	public function testIsUrlSyntaxOk(){
		$this->assertTrue(Helper::isURLSyntaxOk('http://wolxxx.de'));
	}

	public function testGetIpIfRemoteAddrIsNotSet(){
		$_SERVER['REMOTE_ADDR'] = null;
		$this->assertSame('127.0.0.1', Helper::getUserIP());
	}

	public function testGetIpIfRemoteAddrIsSet(){
		$_SERVER['REMOTE_ADDR'] = '1.2.3.4';
		$this->assertSame('1.2.3.4', Helper::getUserIP());
	}

	public function testDebugIsDisabledByDefault(){
		$this->assertFalse(Helper::isDebugEnabled());
	}

	public function testDebugIsEnabledAfterEnabling(){
		Stack::getInstance()->set('debug', true);
		$this->assertTrue(Helper::isDebugEnabled());
	}

	public function testDebugIsDisabledAfterEnablingAndClearingStack(){
		Stack::getInstance()->set('debug', true);
		$this->assertTrue(Helper::isDebugEnabled());
		Stack::getClearInstance();
		$this->assertFalse(Helper::isDebugEnabled());
	}

	public function testNormalizeString(){
		$string = 'Hällö! wWü gehts?!';
		$this->assertSame('haelloe! wWue gehts?!', Helper::normalizeString($string));
	}

	public function testCleanString(){
		$string = 'Hällö! wWü gehts?!';
		$this->assertSame('Haelloe!_wWue_gehts?!', Helper::cleanString($string));
	}

	public function testRemoveHtmlTags(){
		$text = '<a href="test.html">huhu</a> test';
		$this->assertSame('huhu test', CoreHelper::removeTagsFromText($text));

		$text = '<h1>huhu</h1> test';
		$this->assertSame('huhu test', CoreHelper::removeTagsFromText($text));
	}

	public function testRemoveLinkTagFromText(){
		$text = '<a href="test.html">huhu</a> test';
		$this->assertSame('huhu test', CoreHelper::removeSingleTagFromText($text, 'a'));

		$text = '<h1>huhu</h1> test';
		$this->assertSame($text, CoreHelper::removeSingleTagFromText($text, 'a'));
	}

	public function testDateByTimeStampDefaultFormat(){
		$this->assertEquals(CoreHelper::getDateByTimestamp(null, 12334), '1970-01-01 04:25:34');
	}

	public function testDateToTimestamp(){
		$this->assertEquals(CoreHelper::dateToTimestamp('1970-01-01 04:25:34'), 12334);
	}

	public function testRenderDate(){
		$this->expectOutputString('01.01.1970, 01:00');
		CoreHelper::renderDate(CoreHelper::getDate(null, 1));
	}

	public function testGetDateForYear(){
		$this->assertEquals(CoreHelper::getDate('Y'), 2013);
	}

	public function testDateByTimestamp(){
		$this->assertEquals(CoreHelper::getDateByTimestamp('Y', self::$timestamp), 2012);
	}

	public function testDateForTimestampNull(){
		$this->assertEquals(CoreHelper::getDateByTimestamp('Y', null), 2013);
	}

	public function testRenderUTCDate(){
		$this->expectOutputString('01.01.1970, 01:00');
		CoreHelper::renderUTCDate(CoreHelper::getDate(null, 1));
	}

	public function testCreateUTCDate(){
		$this->assertEquals(CoreHelper::createUTCDate(CoreHelper::getDate(null, self::$timestamp)), 'Tue, 24 Jul 2012 16:23:16 +0000');
	}

	public function testFileSize(){
		$size = 4;
		$this->assertEquals(CoreHelper::fileSize($size), $size.' B');

		$size = 1024;
		$this->assertEquals(CoreHelper::fileSize($size), '1 KB');

		$size = 1023;
		$this->assertEquals(CoreHelper::fileSize($size), '1023 B');

		$size = 1024*1024;
		$this->assertEquals(CoreHelper::fileSize($size), '1 MB');

		$size = 1024 * 1024 * 1024 + 30;
		$this->assertEquals(CoreHelper::fileSize($size), '2 GB');

		$size = 1024 * 1024 * 1024;
		$this->assertEquals(CoreHelper::fileSize($size), '1 GB');

		$size = 1024 * 1024 * 1024 * 1024;
		$this->assertEquals(CoreHelper::fileSize($size), '1099511627776');
	}

	public function testArrayChunk(){
		$this->assertEquals(CoreHelper::createUTCDate(CoreHelper::getDate(null, self::$timestamp)), 'Tue, 24 Jul 2012 16:23:16 +0000');
		$this->assertEquals(CoreHelper::array_split(range(0,10), 2), array(range(0,5), range(6,10)));
		$this->assertEquals(CoreHelper::array_split(range(0,8), 3), array(range(0,2), range(3,5), range(6,8)));
		$this->assertEquals(CoreHelper::array_split(range(0,10), 1), array(range(0,10)));
	}

	public function testIsImage(){
		$this->assertEquals(CoreHelper::isImage(__DIR__.'/_src/header.png'), true);
		$this->assertEquals(CoreHelper::isImage(__FILE__), false);
	}

	public function testGetDocRoot(){
		$root = CoreHelper::getDocRoot();
		$test = substr(__DIR__, 0, strlen($root));
		$this->assertEquals(CoreHelper::getDocRoot(), $test);
	}

	public function testGeneratePassword(){
		$this->assertEquals(strlen(CoreHelper::generatePassword(10)), 10);
		$this->assertEquals(strlen(CoreHelper::generatePassword(1)), 1);
	}

	public function testGetCurrentURLForEmptyServer(){
		unset($_SERVER['REQUEST_URI']);
		unset($_SERVER['HTTP_HOST']);
		$this->assertEquals(CoreHelper::getCurrentURL(), 'localhost');
	}

	public function testGetCurrentURLWithHttps(){
		$_SERVER['REQUEST_URI'] = '/pew/pew';
		$_SERVER['HTTP_HOST'] = 'test.wolxxx.de';
		$_SERVER['HTTPS'] = 'on';
		$this->assertEquals(CoreHelper::getCurrentURL(), 'https://test.wolxxx.de/pew/pew');
	}

	public function testGetCurrentURLWithoutHttps(){
		$_SERVER['REQUEST_URI'] = '/pew/pew';
		$_SERVER['HTTP_HOST'] = 'test.wolxxx.de';
		unset($_SERVER['HTTPS']);
		$this->assertEquals(CoreHelper::getCurrentURL(), 'http://test.wolxxx.de/pew/pew');
	}

	public function testGetCurrentURIWithoutURI(){
		unset($_SERVER['REQUEST_URI']);
		$this->assertEquals(CoreHelper::getCurrentURI(), 'localhost');
	}

	public function testGetCurrentURIWithURI(){
		$_SERVER['REQUEST_URI'] = '/pew/pew';
		$this->assertEquals(CoreHelper::getCurrentURI(), '/pew/pew');
	}

	public function testCheckMailSyntaxForFailure(){
		$mail = 'abcd';
		$this->assertFalse(CoreHelper::isMailSyntaxOk($mail));
	}

	public function testCheckMailSyntaxForSuccess(){
		$mail = 'wolxXx@wolxXx.de';
		$this->assertTrue(CoreHelper::isMailSyntaxOk($mail));
	}

	public function testCheckMailSyntaxForSuccessAndDeprecated(){
		$mail = 'wolxXx@wolxXx.de';
		$this->assertTrue(CoreHelper::checkMailSyntax($mail));
	}

	public function testDeprecatedCheckMailSyntaxForFailure(){
		$mail = 'abcd';
		$this->assertFalse(CoreHelper::isMailSyntaxOk($mail));
	}

	public function testDeprecatedCheckMailSyntaxForSuccess(){
		$mail = 'wolxXx@wolxXx.de';
		$this->assertTrue(CoreHelper::isMailSyntaxOk($mail));
	}

	public function testFloatToDecimalPrice(){
		$this->assertEquals('13,37€', CoreHelper::floatToDecimalPrice('13.37'));
	}

	public function testFloatToDecimal(){
		$this->assertEquals('13,37', CoreHelper::floatToDecimal('13.37'));
	}

	public function testCutText(){
		$textBase = range(0,10);
		$textBase = implode('', $textBase);
		$text = $textBase.' test.';
		$this->assertSame($text, CoreHelper::cutText($text, 1000));
		$this->assertSame('012345678910 ...', CoreHelper::cutText($text, 1));
		$this->assertSame('0', CoreHelper::cutText($text, 1, null, true));
		$this->assertSame('01', CoreHelper::cutText($text, 2, null, true));
		$this->assertSame('0123...', CoreHelper::cutText($text, 7, null, true));
		$this->assertSame('0...', CoreHelper::cutText($text, 4, null, true));
	}

	public function testSecondsToRemainingTime(){
		$seconds = 100;
		$this->assertSame('01:40', CoreHelper::secondsToRemainingTime($seconds));
		$this->assertSame('00:01:40', CoreHelper::secondsToRemainingTime($seconds, false));
	}

	public function testUploadErrorToString(){
		$this->assertSame('Konnte Datei nicht schreiben.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_CANT_WRITE));
		$this->assertSame('Dateityp nicht akzeptiert.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_EXTENSION));
		$this->assertSame('Datei zu groß.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_FORM_SIZE));
		$this->assertSame('Datei zu groß.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_INI_SIZE));
		$this->assertSame('Keine Datei gesendet.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_NO_FILE));
		$this->assertSame('Kein Temp-Ordner gefunden.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_NO_TMP_DIR));
		$this->assertSame('Kein Fehler aufgetreten.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_OK));
		$this->assertSame('Unvollständiger Upload.', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_PARTIAL));
		$this->assertSame('Unbekannter Fehler. Mulder und Scully ermitteln schon!', CoreHelper::uploadErrorNumberToString(UPLOAD_ERR_PARTIAL+ 10000));
	}

	public function testGetFileName(){
		$this->assertSame('CoreHelperTest.php', CoreHelper::getFileName(__FILE__));
	}

	public function testGetFileExtension(){
		$this->assertSame('.php', CoreHelper::getFileExtension(__FILE__));
	}

	public function testGetFileExtensionWithPrependPointTrue(){
		$this->assertSame('.php', CoreHelper::getFileExtension(__FILE__, true));
	}

	public function testGetFileExtensionWithPrependPointFalse(){
		$this->assertSame('php', CoreHelper::getFileExtension(__FILE__, false));
	}

	public function testScanDirectoryNonRecursive(){
		$files = CoreHelper::scanDirectory(__DIR__.'/_src/ScanDirTestDir');
		$files[0] = CoreHelper::getFileName($files[0]);
		$this->assertSame(array('pewpew.txt'), $files);
	}

	public function testScanDirectoryRecursively(){
		$files = CoreHelper::scanDirectory(__DIR__.'/_src/ScanDirTestDir', true);
		$files[0] = CoreHelper::getFileName($files[0]);
		$files[1] = CoreHelper::getFileName($files[1]);
		$this->assertContains('pewpew.txt', $files);
		$this->assertContains('luuuke.php', $files);
	}

	public function testAddSplash(){
		CoreHelper::clearSplashes();
		CoreHelper::addSplash('pewpew');
		$this->assertSame(array('pewpew'), CoreHelper::getPlainSplashes());
	}

	public function testAddMultipleSplash(){
		CoreHelper::clearSplashes();
		CoreHelper::addSplash('pewpew');
		CoreHelper::addSplash('pewpew2');
		$this->assertSame(array('pewpew', 'pewpew2'), CoreHelper::getPlainSplashes());
	}

	public function testClearSplashes(){
		CoreHelper::clearSplashes();
		CoreHelper::addSplash('pewpew');
		$this->assertSame(array('pewpew'), CoreHelper::getPlainSplashes());
		CoreHelper::clearSplashes();
		$this->assertSame(null, CoreHelper::getPlainSplashes());
	}
}