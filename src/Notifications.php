<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2020 Percipio Global Ltd
 */

namespace percipiolondon\notifications;

use Craft;
use craft\base\Plugin;
use craft\console\Application as ConsoleApplication;
use craft\web\twig\variables\CraftVariable;
use percipiolondon\notifications\models\Notification;
use percipiolondon\notifications\models\Settings;
use percipiolondon\notifications\variables\NotificationsVariable;
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
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 *
 * @property  NotificationsServiceService $notificationsService
 */
class Notifications extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Notifications::$plugin
     *
     * @var Notifications
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Notifications::$plugin
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

        // Add in our console commands
        if (Craft::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'percipiolondon\notifications\console\controllers';
        }

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('notifications', NotificationsVariable::class);
            }
        );

        // Register the events for each Notification that's configured
        foreach ($this->getSettings()->notifications as $notificationSettings) {
            Event::on(
                $notificationSettings['class'],
                $notificationSettings['event'],
                function(Event $event) use ($notificationSettings) {
                    /* @var Notification $notification */
                    $notification = $notificationSettings['notification'];

                    Notifications::$plugin->notificationsService->send(
                        new $notification(['event' => $event])
                    );
                }
            );
        }
    }

    // Protected Methods
    // =========================================================================
    protected function createSettingsModel()
    {
        return new Settings();
    }
}
