<?
/**
 * sets the config for the application
 * this may be auth tokens for external services
 *
 * * rename the defaultConfigurateApplication method into configurateApplication and set the config values
 *
 * @author wolxXx
 * @version 1.0
 * @package application
 * @subpackage config
 */
abstract class AppConfig extends CoreConfig{
	/**
	 * (non-PHPdoc)
	 * @see CoreConfig::configureApplication()
	 */
	public function defaultConfigureApplication(){}
}