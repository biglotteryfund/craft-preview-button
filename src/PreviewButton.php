<?php
/**
 * Preview Button plugin for Craft CMS 3.x
 *
 * Adds a preview button to the entry editor screen to allow previewing of draft/revision entries
 *
 * @link      https://www.github.com/biglotteryfund
 * @copyright Copyright (c) 2018 Big Lottery Fund
 */

namespace biglotteryfund\previewbutton;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Big Lottery Fund
 * @package   PreviewButton
 * @since     1.0.0
 *
 */
class PreviewButton extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * PreviewButton::$plugin
     *
     * @var PreviewButton
     */
    public static $plugin;

    public $hasCpSettings = true;

    protected function createSettingsModel()
    {
        return new \biglotteryfund\previewbutton\models\Settings();
    }

    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('preview-button/settings', [
            'settings' => $this->getSettings()
        ]);
    }


    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * PreviewButton::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();

        self::$plugin = $this;

        Craft::$app->getView()->hook('cp.entries.edit.details', function(array &$context) {
            $entry = $context['entry'];
            
            $versionId = isset($entry->versionId) ? $entry->versionId : false;
            $draftId = isset($entry->draftId) ? $entry->draftId : false;
        
            $html = '';
            
            if ($versionId || $draftId) {

                // this will be prefixed with the siteUrl
                // (if we look for the entry path, it's empty for non-live content)
                $siteUrl = \craft\helpers\UrlHelper::siteUrl();
                $previewUrl = $this->getSettings()->urlBase . '/';
                $previewLink = str_replace($siteUrl, $previewUrl, $entry->url);

                if ($versionId) {
                    $previewLink .= '?' . $this->getSettings()->versionParameter . '=' . $versionId;
                    $text = 'Preview this version';
                } else {
                    $previewLink .= '?' . $this->getSettings()->draftParameter . '=' . $draftId;
                    $text = 'Preview this draft';
                }

                $html = '
                <div class="meta">
                    <div class="field">
                        <div class="heading">
                            <label>Preview</label>
                        </div>
                        <div class="input ltr">
                            <a href="' . $previewLink . '" class="btn">' . $text . '</a>
                        </div>
                    </div>
                </div>';
            }

            return $html;
        });


/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'preview-button',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
