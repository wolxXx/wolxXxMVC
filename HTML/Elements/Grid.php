<?
/**
 * a grid element
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 *
 */
class Grid extends ContainableDomElementAbstract{
	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'size' => '1',
			'class' => 'grid_1'
		);
	}

	/**
	 * setter for the grid size
	 *
	 * @param integer $size
	 * @return Grid
	 */
	public function setSize($size){
		$this->set('size', $size);
		return $this;
	}

	/**
	 * clears the floating
	 *
	 * @return Grid
	 */
	public function clear(){
		HTML::renderClear();
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		?>
			<div class="grid_<?= $this->get('size') ?>">
				<? foreach($this->children as $current){
					$current->display();
				} ?>
			</div>
		<?
		return $this;
	}
}