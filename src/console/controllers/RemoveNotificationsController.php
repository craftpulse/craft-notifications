<?php

namespace percipioglobal\notifications\console\controllers;

use Craft;
use craft\console\Controller;
use craft\helpers\DateTimeHelper;
use Exception;
use percipioglobal\notifications\records\NotificationsRecord;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

class RemoveNotificationsController extends Controller
{
    public string $time = "-1 month";

    public function options(mixed $actionID): array
    {
        $options = parent::options($actionID);
        $options[] = 'time';

        return $options;
    }

    /**
     * Delete notifications older than a time indication which will be added in the strtotime PHP function - default -1 month
     *
     * @throws Exception
     */
    public function actionIndex(): int
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
                $this->stdout('done' . PHP_EOL, BaseConsole::FG_GREEN);
            }

            Craft::warning('Notifications deleted: ' . $count, __METHOD__);
        }

        $this->stdout("Deleted $count read notifications." . PHP_EOL);

        return ExitCode::OK;
    }
}
