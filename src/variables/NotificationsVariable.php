<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */

namespace craftpulse\notifications\variables;

use craft\helpers\DateTimeHelper;

use craftpulse\notifications\Notifications;

/**
 * Notifications Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.notifications }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 */
class NotificationsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $user
     *
     * @return array
     */
    public function all($user = null)
    {
        return Notifications::$plugin->notificationsService->getAll($user);
    }

    /**
     * Return all unread notifications
     *
     * @param null $user
     *
     * @return array
     */
    public function unread($user = null): array
    {
        return Notifications::$plugin->notificationsService->getAllUnread($user);
    }

    public function markAsRead($notification = null)
    {
        $now = DateTimeHelper::currentUTCDateTime()->format('Y-m-d H:i:s');
        return Notifications::$plugin->notificationsService->updateReadStatus($notification, $now);
    }

    public function markAsUnread($notification = null)
    {
        return Notifications::$plugin->notificationsService->updateReadStatus($notification, null);
    }
}
