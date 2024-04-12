<?php
/**
 * Notifications plugin for Craft CMS 4.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://craftpulse.com
 * @copyright Copyright (c) 2024 CraftPulse
 */

namespace craftpulse\notifications\models;

use craft\base\Model;
use yii\base\Event;

/**
 * @author    CraftPulse
 * @package   Notifications
 * @since     1.0.0
 */
class Notification extends Model
{
    /**
     * The unique identifier for the notification.
     *
     * @var string
     */
    public string $id;

    /**
     * @var Event|null
     */
    public ?Event $event = null;

    /**
     * Constructor
     *
     * @param mixed $config
     */
    public function __construct($config)
    {
        $config = ['event' => $config];
        parent::__construct($config);
    }

    /**
     * The via function determines which channels will be used to send the notification to.
     * Each channel consists of the name as the key and the receiver(s) as the value in
     * the format that the channel expects.
     *
     * return [
     *     'database' => Craft::$app->getUsers()->getUserByUsernameOrEmail('info@example.com'),
     *     'slack' => '<YOUR_SLACK_WEBHOOK_URL>',
     *     'mail' => 'info@example.com',
     * ];
     *
     * @return array
     */
    public function via(): array
    {
        return [];
    }
}
