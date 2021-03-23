<?php
/**
 * @package   OSDonate
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2016-2020 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSDonate.
 *
 * OSDonate is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSDonate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSDonate.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die();

require_once 'library/Installer/include.php';

use Alledia\Installer\AbstractScript;
use Joomla\CMS\Filesystem\Folder;

/**
 * Custom installer script
 */
class Mod_OSDonateInstallerScript extends AbstractScript
{
    public function postFlight($type, $parent)
    {
        parent::postFlight($type, $parent);

        $files = Folder::files(JPATH_SITE . '/language', 'mod_osdonate', true, true);
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}
