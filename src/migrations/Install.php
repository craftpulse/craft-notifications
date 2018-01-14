<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://rias.be
 * @copyright Copyright (c) 2018 Rias
 */

namespace rias\notifications\migrations;

use rias\notifications\Notifications;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Notifications Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Rias
 * @package   Notifications
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // notifications_notifications table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%notifications_notifications}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%notifications_notifications}}',
                [
                    'id' => $this->primaryKey(),
                    'uid' => $this->uid(),
                    'type' => $this->string(255)->notNull(),
                    'notifiable' => $this->integer()->unsigned()->notNull(),
                    'data' => $this->text()->notNull(),
                    'read_at' => $this->timestamp()->null(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
    // notifications_notifications table
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%notifications_notifications}}', 'notifiable'),
            '{{%notifications_notifications}}',
            'notifiable',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // notifications_notifications table
        $this->dropTableIfExists('{{%notifications_notifications}}');
    }
}
