<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://rias.be
 * @copyright Copyright (c) 2018 Rias
 */

namespace rias\notifications\variables;

use craft\elements\User;
use rias\notifications\Notifications;

use Craft;

/**
 * Notifications Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.notifications }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class NotificationsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Return all notifications
     *
     * @return array
     */
    public function getAllUnread()
    {
        return Notifications::$plugin->notificationsService->getAllUnread();
    }

    public function markAsRead($notification)
    {
        return Notifications::$plugin->notificationsService->markAsRead($notification);
    }
}
