<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */

namespace craftpulse\notifications\events;

use yii\base\Event;

/**
 * RegisterChannelsEvent class.

 */
class RegisterChannelsEvent extends Event
{
    // Properties
    // =========================================================================

    /**
     * @var array List of registered actions for the element type.
     */
    public $channels = [];
}
