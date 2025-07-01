<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */
namespace craftpulse\notifications\events;

use craftpulse\notifications\records\NotificationsRecord;
use yii\base\Event;

/**
 * Class SendEvent
 */
class SendEvent extends Event
{
    /**
     * @var mixed The notifiable.
     */
    public $notifiable;

    /**
     * @var NotificationsRecord The notification about to be sent.
     */
    public $notification;

    /**
     * @var string The channel on which the notification is about to be sent.
     */
    public $channel;

    /**
     * @var bool Whether we send the notification
     */
    public $sendNotification = true;

    /**
     * @var mixed The response after sending the event
     */
    public $response = null;
}
