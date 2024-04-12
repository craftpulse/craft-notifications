<?php

namespace craftpulse\notifications\events;

use yii\base\Event;

/**
 * RegisterChannelsEvent class.
 *
 * @author    CraftPulse
 * @package   Notifications
 * @since     1.0.0
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
