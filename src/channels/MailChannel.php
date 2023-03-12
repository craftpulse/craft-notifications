<?php

namespace percipiolondon\notifications\channels;

use craft\mail\Message;
use Exception;
use percipiolondon\notifications\models\Notification;

/**
 * Class MailChannel
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
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
