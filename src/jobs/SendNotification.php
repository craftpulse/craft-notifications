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
        /* @var ElementInterface $elementType */
        $elementType = new $this->notificationSettings['elementType'];

        // Create the query
        $query = $elementType->find();
        $query->limit = null;
        Craft::configure($query, $this->notificationSettings['criteria']);

        // Make sure this entry adheres to the criteria
        if (in_array($this->event->sender->id, $query->ids())) {
            /* @var Notification $notification */
            $notification = $this->notificationSettings['notification'];
            Notifications::$plugin->notificationsService->send(
                new $notification($this->event->sender),
                $this->event
            );
        }
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
