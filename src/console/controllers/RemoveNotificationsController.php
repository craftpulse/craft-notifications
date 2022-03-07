<?php

namespace percipioglobal\notifications\console\controllers;

use craft\console\Controller;
use craft\helpers\DateTimeHelper;

use percipioglobal\notifications\records\NotificationsRecord;
use Craft;
use yii\console\ExitCode;

class RemoveNotificationsController extends Controller
{
    public $time = "-1 month";

    public function options($actionID)
    {
        $options = parent::options($actionID);
        $options[] = 'time';

        return $options;
    }

    /**
     * Delete notifications older than a time indication which will be added in the strtotime PHP function - default -1 month
     */
    public function actionIndex()
    {
        $expiration = DateTimeHelper::toDateTime(strtotime($this->time))->format('Y-m-d H:i:s');
        $notifications = NotificationsRecord::find()
            ->andWhere('read_at IS NOT NULL')
            ->andWhere(['<', 'read_at', $expiration]);

        $count = $notifications->count();
        echo "Found $count notifications.\n";

        if ($count) {
            foreach ($notifications->all() as $notification) {
                $this->stdout(" - Deleting notification {$notification->id} ... ");
                $notification->delete();
                $this->stdout('done' . PHP_EOL, Console::FG_GREEN);
            }

            Craft::warning('Notifications deleted: ' . $count, __METHOD__);
        }

        $this->stdout("Deleted $count read notifications." . PHP_EOL);

        return ExitCode::OK;
    }
}