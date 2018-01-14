<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://rias.be
 * @copyright Copyright (c) 2018 Rias
 */

namespace rias\notifications\services;

use craft\elements\User;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use craft\mail\Mailer;
use GuzzleHttp\Client as HttpClient;
use Ramsey\Uuid\Uuid;
use rias\notifications\channels\DatabaseChannel;
use rias\notifications\channels\MailChannel;
use rias\notifications\channels\SlackWebhookChannel;
use rias\notifications\events\RegisterChannelsEvent;
use rias\notifications\events\SendEvent;

use Craft;
use craft\base\Component;
use rias\notifications\models\Notification;
use rias\notifications\records\NotificationsRecord;
use yii\base\Event;
use yii\base\InvalidCallException;

/**
 * NotificationsService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class NotificationsService extends Component
{
    /**
     * @event SubmissionEvent The event that is triggered before a notification is sent
     */
    const EVENT_BEFORE_SEND = 'beforeSend';

    /**
     * @event SubmissionEvent The event that is triggered after a notification is sent
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * @event RegisterChannelsEvent An event to register new channels into the notificationsService
     */
    const EVENT_REGISTER_CHANNELS = 'registerChannels';

    // Public Methods
    // =========================================================================
    /**
     * @inheritdoc
     */
    public static function channels()
    {
        $channels = static::defineChannels();

        // Give plugins a chance to modify them
        $event = new RegisterChannelsEvent([
            'channels' => $channels
        ]);
        Event::trigger(static::class, self::EVENT_REGISTER_CHANNELS, $event);

        return $event->channels;
    }


    /**
     * Send the given notification to the given notifiable entities.
     *
     * @param  Notification $notification
     * @param Event $event
     *
     * @return void
     */
    public function send(Notification $notification)
    {
        $original = clone $notification;

        foreach ($notification->via() as $channel => $notifiables) {
            $notifiables = $this->formatNotifiables($notifiables);

            foreach ($notifiables as $notifiable) {
                $notificationId = Uuid::uuid4()->toString();
                $notification = clone $original;

                if (! $notification->id) {
                    $notification->id = $notificationId;
                }

                if (! $this->shouldSendNotification($notifiable, $notification, $channel)) {
                    continue;
                }

                $response = $this->channel($channel)->send($notifiable, $notification);

                $event = new SendEvent([
                    'notification' => $notification,
                    'notifiable' => $notifiable,
                    'channel' => $channel,
                    'response' => $response,
                ]);

                $this->trigger(self::EVENT_AFTER_SEND, $event);
            }
        }
    }

    /**
     * Get all notifications for a certain User
     *
     * @param User $user
     *
     * @return array
     */
    public function getAll(User $user = null)
    {
        // If there's no passed user, get the current logged in user
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }

        if ($user) {
            $notifications = NotificationsRecord::find()->where(['notifiable' => $user->id])->all();
            return $this->formatNotificationData($notifications);
        }

        // No notifications when we don't have a passed in or logged in user
        return [];
    }

    /**
     * Get all unread notifications for a certain User
     *
     * @param User $user
     *
     * @return array
     */
    public function getAllUnread(User $user = null)
    {
        // If there's no passed user, get the current logged in user
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }

        if ($user) {
            $notifications = NotificationsRecord::find()->where(['notifiable' => $user->id, 'read_at' => null])->all();
            return $this->formatNotificationData($notifications);
        }

        // No notifications when we don't have a passed in or logged in user
        return [];
    }

    /**
     * Mark a notification as read
     *
     * @param $notifications
     */
    public function markAsRead($notifications = null)
    {
        // If we don't pass notifications, mark all as read for the current logged in user
        $user = Craft::$app->getUser();
        if ($user && is_null($notifications)) {
            $notifications = NotificationsRecord::find()->where(['notifiable' => $user->getId()])->all();
        }

        // Make sure we have a collection to loop over
        $notifications = collect($notifications);

        $notificationIds = $notifications->map(function ($notification) {
            return is_object($notification) ? $notification->id : $notification;
        });

        // Update the read notifications
        if (! is_null($notificationIds)) {
            $now = DateTimeHelper::currentUTCDateTime()->format('Y-m-d H:i:s');
            NotificationsRecord::updateAll(['read_at' => $now], ['id' => $notificationIds]);
        }
    }

    /**
     * Decode notification data
     *
     * @param $notifications
     *
     * @return array
     */
    protected function formatNotificationData($notifications)
    {
        return collect($notifications)->map(function ($notification) {
            $notification->data = json_decode($notification->data);
            return $notification;
        });
    }

    /**
     * Determines if the notification can be sent.
     *
     * @param  mixed  $notifiable
     * @param  mixed  $notification
     * @param  string  $channel
     * @return bool
     */
    protected function shouldSendNotification($notifiable, $notification, $channel)
    {
        $event = new SendEvent([
            'notification' => $notification,
            'notifiable' => $notifiable,
            'channel' => $channel,
        ]);

        $this->trigger(self::EVENT_BEFORE_SEND, $event);

        return $event->sendNotification;
    }

    /**
     * Format the notifiables into an array if necessary.
     *
     * @param  mixed  $notifiables
     * @return array
     */
    protected function formatNotifiables($notifiables)
    {
        // Notifiables can be an anonymous function that returns an array
        if (is_callable($notifiables)) {
            $notifiables = $notifiables();
        }

        // Always make sure we have an array
        if (! is_array($notifiables)) {
            return [$notifiables];
        }

        return $notifiables;
    }

    /**
     * Defines the available channels.
     *
     * @return array The available channels.
     * @see channels()
     */
    protected static function defineChannels()
    {
        return [
            'database' => function () {
                return self::createDatabaseChannel();
            },
            'mail' => function () {
                return self::createMailChannel();
            },
            'slack' => function () {
                return self::createSlackChannel();
            },
        ];
    }

    /**
     * Get a channel instance.
     *
     * @param  string|null  $name
     * @return mixed
     */
    protected function channel($name = null)
    {
        if (!isset(self::channels()[$name])) {
            throw new InvalidCallException("No channel {$name} exists.");
        }

        return call_user_func(self::channels()[$name]);
    }

    /**
     * Create a database channel to send notifications to
     *
     * @return DatabaseChannel
     */
    protected static function createDatabaseChannel()
    {
        return new DatabaseChannel();
    }

    /**
     * Create a slack channel to send notifications to
     *
     * @return SlackWebhookChannel
     */
    protected static function createSlackChannel()
    {
        return new SlackWebhookChannel(new HttpClient());
    }

    /**
     * Create an E-mail channel to send notifications to
     *
     * @return MailChannel
     */
    protected static function createMailChannel()
    {
        return new MailChannel();
    }
}
