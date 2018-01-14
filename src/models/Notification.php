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
 * Notification Record
 *
 * ActiveRecord is the base class for classes representing relational data in terms of objects.
 *
 * Active Record implements the [Active Record design pattern](http://en.wikipedia.org/wiki/Active_record).
 * The premise behind Active Record is that an individual [[ActiveRecord]] object is associated with a specific
 * row in a database table. The object's attributes are mapped to the columns of the corresponding table.
 * Referencing an Active Record attribute is equivalent to accessing the corresponding table column for that record.
 *
 * http://www.yiiframework.com/doc-2.0/guide-db-active-record.html
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class Notification
{
    /**
     * The unique identifier for the notification.
     *
     * @var string
     */
    public $id;

    /**
     * @var ElementInterface
     */
    public $element = null;

    public function __construct($element)
    {
        $this->element = $element;
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
     * @param Event $event
     *
     * @return array
     */
    public function via(Event $event)
    {
        return [];
    }
}
