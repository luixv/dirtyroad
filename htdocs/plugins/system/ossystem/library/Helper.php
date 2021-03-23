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

namespace Alledia\OSSystem;

use Alledia\Framework\Joomla\Extension;
use Alledia\Framework;
use JFile;
use JResponse;

defined('_JEXEC') or die();

jimport('joomla.filesystem.file');


/**
 * Helper class
 */
abstract class Helper
{
    public static function addCustomFooterIntoNativeComponentOutput($element)
    {
        // Check if the specified extension is from Alledia
        $extension = Extension\Helper::getExtensionForElement($element);
        $footer    = $extension->getFooterMarkup();

        if (!empty($footer)) {
            // Inject the custom footer
            if (version_compare(JVERSION, '3.0', 'lt')) {
                $body = JResponse::getBody();
                $body = preg_replace('#(<p\salign="center">Joomla!\s[0-9.\s&;]*</p>)#i', $footer . '$1', $body);
                JResponse::setBody($body);
            } else {
                $app  = Framework\Factory::getApplication();
                $app->setBody(
                    str_replace('</section>', '</section>' . $footer, $app->getBody())
                );
            }
        }
    }

    public static function revertCARootFileToOriginal()
    {
        // Get the original Joomla file
        $joomlaCACertificatesPath = JPATH_SITE . '/libraries/joomla/http/transport/cacert.pem';
        $backupCACertificatesPath = JPATH_SITE . '/libraries/joomla/http/transport/cacert.pem.ossystem-backup';

        if (file_exists($backupCACertificatesPath)) {
            if (file_exists($joomlaCACertificatesPath)) {
                JFile::delete($joomlaCACertificatesPath);
            }

            JFile::move($backupCACertificatesPath, $joomlaCACertificatesPath);
        }
    }
}
