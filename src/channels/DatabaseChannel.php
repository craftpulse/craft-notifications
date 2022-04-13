<?php

namespace percipiolondon\notifications\channels;

use craft\base\ElementInterface;
use craft\helpers\Json;
use percipiolondon\notifications\models\Notification;
use percipiolondon\notifications\records\NotificationsRecord;
use RuntimeException;

/**
 * Class DatabaseChannel
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 */
class DatabaseChannel
{
    public function send(ElementInterface $notifiable, Notification $notification): bool
    {
        $notificationsRecord = new NotificationsRecord();
        $notificationsRecord->uid = $notification->id;
        $notificationsRecord->notifiable = $notifiable->getId();
        $notificationsRecord->type = get_class($notification);
        $notificationsRecord->data = Json::encode($this->getData($notifiable, $notification));
        $notificationsRecord->read_at = null;

        return $notificationsRecord->save();
    }

    /**
     * Get the data for the notification.
     *
     * @param  ElementInterface  $notifiable
     * @param  Notification $notification
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function getData(mixed $notifiable, Notification $notification): array
    {
        if (method_exists($notification, 'toDatabase')) {
            $data = $notification->toDatabase($notifiable);

            return is_array($data) ? $data : [$data];
        } elseif (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException(
            'Notification is missing toDatabase / toArray method.'
        );
    }
}
