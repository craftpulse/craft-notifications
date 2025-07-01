<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */

namespace craftpulse\notifications\channels;

use craft\mail\Message;
use Exception;
use craftpulse\notifications\models\Notification;

/**
 * Class MailChannel
 */
class MailChannel
{
    /**
     * Send the given notification.
     *
     * @param  string        $notifiable
     * @param  Notification $notification
     *
     * @return void
     * @throws Exception
     */
    public function send(string $notifiable, Notification $notification): void
    {
        $channelResult = $notification->toMail($notifiable);
        $messages = is_array($channelResult) ? $channelResult : [$channelResult];

        foreach ($messages as $message) {
            if (!$message instanceof Message) {
                throw new Exception("Message needs to be an instance of craft\mail\Message");
            }

            $message->send();
        }
    }
}
