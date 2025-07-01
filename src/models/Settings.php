<?php
/**
 * Notifications plugin for Craft CMS 5.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craft-pulse.com
 * @copyright Copyright (c) 2025 CraftPulse
 */

namespace craftpulse\notifications\models;

use craft\base\Model;

class Settings extends Model
{
    public $notifications = [];
    public $services = [];
}
