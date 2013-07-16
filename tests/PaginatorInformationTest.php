<?
/**
 * @codeCoverageIgnore
 */
class PaginatorInformationTest extends  PHPUnit_Framework_TestCase{
	function testDefaultConstructor(){
		$paginator = new PaginatorInformation();
		$this->assertFalse($paginator->isHidePaginator());
		$this->assertSame(1, $paginator->getPageNumber());
		$this->assertSame(1, $paginator->getPages());
		$this->assertSame('', $paginator->getUrlPrefix());
	}
}