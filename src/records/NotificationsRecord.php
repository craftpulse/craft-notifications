<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2020 Percipio Global Ltd.
 */

namespace percipioglobal\notifications\records;

use craft\records\User;
use percipioglobal\notifications\Notifications;

use Craft;
use craft\db\ActiveRecord;

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
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 *
 * @property string $uid
 * @property int    $notifiable
 * @property string $type
 * @property string $data
 * @property mixed $read_at
 *
 */
class NotificationsRecord extends ActiveRecord
{
    /**
    * @return string the table name
    */
    public static function tableName()
    {
        return '{{%notifications_notifications}}';
    }

    public function rules()
    {
        return [
            [['uid', 'notifiable', 'type', 'data'], 'required'],
            [['read_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }



    public function getNotifiable()
    {
        return $this->hasOne(User::class, ['notifiable' => 'id']);
    }
}
