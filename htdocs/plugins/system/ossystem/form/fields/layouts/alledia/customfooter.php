<?php
/**
 * @package    AllediaFreeDefaultFiles
 * @contact    www.joomlashack.com, help@joomlashack.com
 * @copyright  2018 Open Source Training, LLC. All rights reserved
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of AllediaFreeDefaultFiles.
 *
 * AllediaFreeDefaultFiles is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * AllediaFreeDefaultFiles is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AllediaFreeDefaultFiles.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die();

/**
 * @var array $displayData
 */

$class         = $displayData['class'];
$media         = $displayData['media'];
$jslogo        = $media . '/' . $displayData['jslogo'];
$jedurl        = $displayData['jedurl'];
$showGoProAd     = $displayData['showGoProAd'];
$goProUrl      = $displayData['goProUrl'];
$fromInstaller = $displayData['fromInstaller'];

$footerCss = JHtml::_('stylesheet', $media . '/field_customfooter.css', array('relative' => true, 'pathOnly' => true));
$adminCss  = JHtml::_('stylesheet', $media . '/admin-default.css', array('relative' => true, 'pathOnly' => true));

?>
<link href="<?php echo $footerCss; ?>" rel="stylesheet"/>
<link href="<?php echo $adminCss; ?>" rel="stylesheet"/>
<div class="<?php echo $class; ?>">
    <div class="span-12">
        <?php
        if ($showGoProAd) :
            ?>
            <div class="gopro-ad">
                <?php
                echo JHtml::_(
                    'link',
                    $goProUrl,
                    '<i class="icon-publish"></i>' . JText::_('JOOMLASHACK_FOOTER_GO_PRO_MORE_FEATURES'),
                    'class="gopto-btn" target="_blank"'
                );
                ?>
            </div>
        <?php
        endif;

        if ($jedurl) :
            ?>
            <div class="joomlashack-jedlink">
                <?php
                echo JText::_('JOOMLASHACK_FOOTER_LIKE_THIS_EXTENSION');
                echo '&nbsp;'
                    . JHtml::_(
                        'link',
                        $jedurl,
                        JText::_('JOOMLASHACK_FOOTER_LEAVE_A_REVIEW_ON_JED'),
                        'target="_blank"'
                    );
                echo '&nbsp;' . str_repeat("<i class=\"icon-star\"></i>", 5);
                ?>
            </div>
        <?php
        endif;
        ?>
        <div class="poweredby">
            Powered by
            <?php
            echo JHtml::_(
                'link',
                'https://www.joomlashack.com',
                JHtml::_('image', $jslogo, 'Joomlashack', 'class="joomlashack-logo" width="150"', true),
                'target="_blank"'
            );
            ?>
        </div>

        <div class="joomlashack-copyright">
            &copy; <?php echo date('Y'); ?> Joomlashack.com. All rights reserved.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(event) {
        var footer = document.getElementsByClassName('joomlashack-footer')[0],
            parent = footer.parentElement;

        function hasClass(elem, className) {
            return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
        }

        if (hasClass(parent, 'controls')) {
            var wrapper = document.getElementById('content');

            wrapper.parentNode.insertBefore(footer, wrapper.nextSibling);
        }
    });
</script>
