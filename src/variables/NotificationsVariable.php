<?php
/**
 * Notifications plugin for Craft CMS 4.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craftpulse.com
 * @copyright Copyright (c) 2024 CraftPulse.
 */

namespace craftpulse\notifications\variables;

use craftpulse\notifications\Notifications;

/**
 * Notifications Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.notifications }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    CraftPulse
 * @package   Notifications
 * @since     1.0.0
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
        return Notifications::$plugin->notificationsService->markAsRead($notification);
    }
}
