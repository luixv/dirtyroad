<?php
/**
 * @package   AllediaFreeDefaultFiles
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright Copyright (C) Open Sources Training, LLC, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die();

require_once __DIR__ . '/base.php';

/**
 * Form field to show an advertisement for the pro version
 */
class JFormFieldCustomFooter extends JFormFieldBase
{
    protected $layout = 'alledia.customfooter';

    protected function getInput()
    {
        return $this->getRenderer($this->layout)->render($this->getLayoutData());
    }

    protected function getRenderer($layoutId = 'default')
    {
        $renderer = parent::getRenderer($layoutId);

        if ($layoutId == $this->layout) {
            $renderer->addIncludePath(__DIR__ . '/layouts');
        }

        return $renderer;
    }

    protected function getLayoutData()
    {
        $displayData = parent::getLayoutData();

        $requiredClasses = array(
            'joomlashack-footer',
            'row-fluid'
        );
        if ($this->fromInstaller) {
            $requiredClasses[] = 'installer';
        }

        $classes = array_unique(
            array_filter(
                array_merge(
                    preg_split('/\s/', $displayData['class']),
                    $requiredClasses
                )
            )
        );

        $goProUrl    = (string)$this->element['showgoproad'] ?: '0';
        $showGoProAd = !($goProUrl == '0' || $goProUrl == 'false');
        if ($showGoProAd && !filter_var($goProUrl, FILTER_VALIDATE_URL)) {
            $goProUrl = 'https://www.joomlashack.com/plans';
        }

        $displayData = array_merge(
            $displayData,
            array(
                'class'         => join(' ', $classes),
                'media'         => $this->element['media'],
                'jslogo'        => (string)$this->element['jslogo'] ?: 'joomlashack-logo.png',
                'jshome'        => (string)$this->element['jshome'] ?: 'https://www.joomlashack.com',
                'jedurl'        => (string)$this->element['jedurl'],
                'fromInstaller' => $this->fromInstaller,
                'showGoProAd'   => $showGoProAd,
                'goProUrl'      => $goProUrl
            )
        );

        return $displayData;
    }
}
