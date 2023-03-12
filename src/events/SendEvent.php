<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   MIT
 */
namespace percipiolondon\notifications\events;

use percipiolondon\notifications\records\NotificationsRecord;
use yii\base\Event;

/**
 * Class SendEvent
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
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
