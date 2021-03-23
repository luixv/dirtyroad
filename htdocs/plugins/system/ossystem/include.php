<?php
/**
 * @package   OSSystem
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2016-2020 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSSystem.
 *
 * OSSystem is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSSystem is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSSystem.  If not, see <http://www.gnu.org/licenses/>.
 */

use Alledia\Framework;

defined('_JEXEC') or die();

// Alledia Framework
if (!defined('ALLEDIA_FRAMEWORK_LOADED')) {
    $allediaFrameworkPath = JPATH_SITE . '/libraries/allediaframework/include.php';

    if (file_exists($allediaFrameworkPath)) {
        require_once $allediaFrameworkPath;
    } else {
        $app = JFactory::getApplication();

        if ($app->isAdmin()) {
            $app->enqueueMessage('[Joomlashack System Plugin] Alledia framework not found', 'error');
        }
    }
}

if (defined('ALLEDIA_FRAMEWORK_LOADED') && !defined('OSSYSTEM_LOADED')) {
    define('OSSYSTEM_PATH', __DIR__);
    define('OSSYSTEM_LIBRARY', OSSYSTEM_PATH . '/library');

    Framework\AutoLoader::register('\\Alledia\\OSSystem', OSSYSTEM_LIBRARY);

    // Only for backward compatibility
    if (!class_exists('OSSystemHelper')) {
        include_once 'helper.php';
    }

    if (class_exists('\\Alledia\\OSSystem\\Helper')) {
        define('OSSYSTEM_LOADED', 1);
    }

    // Load additional global language file
    Framework\Factory::getLanguage()
        ->load('plg_system_ossystem', OSSYSTEM_PATH, 'en-GB', true);
}
