<?
/**
 * here you can expand or overwrite the core bootstrap
 * use the hooks {(before, after)x(run, view}) e.g. beforeRun, afterView, etc.
 *
 * @author wolxXx
 * @package application
 * @subpackage config
 * @version 1.0
 *
 */
class Bootstrap extends CoreBootstrap{
	function beforeRun(){
		if(true === Auth::isBanned()){
			die('du bist auf der stillen treppe. und zwar noch '.Helper::secondsToRemainingTime(Auth::getRemainingBanTime()).'.');
		}
	}
}