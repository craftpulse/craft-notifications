<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://rias.be
 * @copyright Copyright (c) 2018 Rias
 */

namespace rias\notifications\models;

use craft\base\ElementInterface;
use craft\base\Model;
use rias\notifications\Notifications;

use Craft;
use craft\db\ActiveRecord;
use yii\base\Event;

/**
 * @author    Rias
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
    public $id;

    /**
     * @var Event
     */
    public $event = null;

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
    public function via()
    {
        return [];
    }
}
