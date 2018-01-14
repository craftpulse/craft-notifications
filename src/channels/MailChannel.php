<?php

namespace rias\notifications\channels;

use craft\mail\Message;
use rias\notifications\models\Notification;

/**
 * Class MailChannel
 *
 * @author    Rias
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
     * @throws \Exception
     */
    public function send(string $notifiable, Notification $notification)
    {
        $message = $notification->toMail($notifiable);

        if (! $message instanceof Message) {
            throw new \Exception("Message needs to be an instance of craft\mail\Message");
        }

        $message->send();
    }
}
