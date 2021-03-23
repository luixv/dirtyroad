<?php

/**
 * @package         Advanced Custom Fields
 * @version         1.2.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/script.install.helper.php';

class PlgFieldsACFCountryInstallerScript extends PlgFieldsACFCountryInstallerScriptHelper
{
	public $alias = 'acfcountry';
	public $extension_type = 'plugin';
	public $plugin_folder = "fields";
	public $show_message = false;

	/**
	 *  Helper method triggered before installation
	 *
	 *  @return  bool
	 */
	public function onBeforeInstall()
	{
		// Fix missing version.php
		if ($this->isInstalled() && !JFile::exists($this->getMainFolder() . '/version.php'))
		{
			$systemVersionPath = JPATH_SITE . '/plugins/system/acf/version.php';
			JFile::copy($systemVersionPath, $this->getMainFolder() . '/version.php');
		}

		return parent::onBeforeInstall();
	}
}
