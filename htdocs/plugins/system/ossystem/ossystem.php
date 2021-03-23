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

use Alledia\Framework\Factory;
use Alledia\Framework\Joomla\Extension\AbstractPlugin;
use Alledia\OSSystem\Helper;

defined('_JEXEC') or die();

include_once 'include.php';

if (class_exists('Alledia\\Framework\\Joomla\\Extension\\AbstractPlugin')) {
    class PlgSystemOSSystem extends AbstractPlugin
    {
        /**
         * Library namespace
         *
         * @var string
         */
        protected $namespace = 'OSSystem';

        /**
         * @return void
         * @throws Exception
         */
        public function onAfterRender()
        {
            $app       = Factory::getApplication();
            $option    = $app->input->getCmd('option');
            $extension = $app->input->getCmd('extension', null);

            // Execute only in admin and in the com_categories component
            if ($app->getName() === 'administrator'
                && $option === 'com_categories'
                && $extension !== 'com_content'
                && !empty($extension)
            ) {
                Helper::addCustomFooterIntoNativeComponentOutput($extension);
            }
        }

        /**
         * This method looks for a backup of cacert.pem file created
         * by an prior release of this plugin, restoring it if found.
         *
         * @return void
         * @throws Exception
         */
        public function onAfterInitialise()
        {
            $app = Factory::getApplication();

            if ($app->getName() === 'administrator') {
                Helper::revertCARootFileToOriginal();
            }
        }
    }
}
