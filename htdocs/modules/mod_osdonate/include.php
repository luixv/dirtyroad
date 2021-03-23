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

use Alledia\Framework\AutoLoader;

if (!defined('MOD_OSDONATE_LOADED')) {
    define('MOD_OSDONATE_LOADED', 1);

    // Alledia Framework
    if (!defined('ALLEDIA_FRAMEWORK_LOADED')) {
        $allediaFrameworkPath = JPATH_SITE . '/libraries/allediaframework/include.php';

        if (file_exists($allediaFrameworkPath)) {
            require_once $allediaFrameworkPath;
        } else {
            JFactory::getApplication()
                ->enqueueMessage('[OSDonate] Alledia framework not found', 'error');
        }
    }

    AutoLoader::register('Alledia\OSDonate', JPATH_SITE . '/modules/mod_osdonate/library');
}
