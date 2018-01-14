<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://rias.be
 * @copyright Copyright (c) 2018 Rias
 */

namespace rias\notifications\jobs;

use craft\base\ElementInterface;
use craft\queue\BaseJob;
use rias\notifications\models\Notification;
use rias\notifications\Notifications;

use Craft;
use yii\base\Event;

/**
 * SendNotification Task
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class SendNotification extends BaseJob
{
    // Public Properties
    // =========================================================================

    /**
     * The notification settings from the config
     *
     * @var string
     */
    public $notificationSettings;

    /**
     * The event that was thrown
     *
     * @var Event
     */
    public $event;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        /* @var Notification $notification */
        $notification = $this->notificationSettings['notification'];

        Notifications::$plugin->notificationsService->send(
            new $notification($this->event)
        );
    }

    // Protected Methods
    // =========================================================================
    /**
     * @inheritdoc
     */
    protected function defaultDescription(): string
    {
        return Craft::t('notifications', 'Sending notifications');
    }
}
