<?php

namespace rias\notifications\channels;

use craft\base\ElementInterface;
use rias\notifications\models\Notification;
use rias\notifications\records\NotificationsRecord;
use RuntimeException;

/**
 * Class DatabaseChannel
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class DatabaseChannel
{
    public function send(ElementInterface $notifiable, Notification $notification)
    {
        $notificationsRecord = new NotificationsRecord();
        $notificationsRecord->uid = $notification->id;
        $notificationsRecord->notifiable = $notifiable->getId();
        $notificationsRecord->type = get_class($notification);
        $notificationsRecord->data = json_encode($this->getData($notifiable, $notification));
        $notificationsRecord->read_at = null;

        return $notificationsRecord->save();
    }

    /**
     * Get the data for the notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification $notification
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toDatabase')) {
            $data = $notification->toDatabase($notifiable);

            return is_array($data) ? $data : $data->data;
        } elseif (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException(
            'Notification is missing toDatabase / toArray method.'
        );
    }
}
