<?php

/**
 * @copyright 	Copyright (c) 2009-2021 Ryan Demmer. All rights reserved
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses
 */
defined('JPATH_PLATFORM') or die;

$title = JText::_('WF_PROFILES_IMPORT_IMPORT');
?>
<joomla-toolbar-button>
    <div class="upload-profile-container">
        <input name="profile_file" accept="application/xml" type="file" />
        <button class="button-import btn btn-small btn-sm btn-outline-primary"><span class="icon-upload text-body" title="<?php echo $title; ?>"></span> <?php echo $title; ?></button>
    </div>
</joomla-toolbar-button>