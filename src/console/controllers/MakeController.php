<?php
/**
 * Notifications plugin for Craft CMS 3.x
 *
 * Send notifications across a variety of delivery channels, including mail and Slack. Notifications may also be stored in a database so they may be displayed in your web interface.
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2020 Percipio Global Ltd.
 */

namespace percipioglobal\notifications\console\controllers;

use craft\helpers\FileHelper;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

use Craft;
use yii\base\Module;
use yii\console\Controller;

/**
 * Default Command
 *
 * The first line of this class docblock is displayed as the description
 * of the Console Command in ./craft help
 *
 * Craft can be invoked via commandline console by using the `./craft` command
 * from the project root.
 *
 * Console Commands are just controllers that are invoked to handle console
 * actions. The segment routing is plugin/controller-name/action-name
 *
 * The actionIndex() method is what is executed if no sub-commands are supplied, e.g.:
 *
 * ./craft notifications/default
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft notifications/default/do-something
 *
 * @author    Percipio Global Ltd.
 * @package   Notifications
 * @since     1.0.0
 */
class MakeController extends Controller
{
    // Public Methods
    // =========================================================================
    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }


    /**
     * Handle notifications/default console commands
     *
     * The first line of this method docblock is displayed as the description
     * of the Console Command in ./craft help
     *
     * @param string $name
     *
     * @return mixed
     * @throws \yii\base\Exception
     * @throws \yii\base\ErrorException
     */
    public function actionIndex($name)
    {
        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ($this->alreadyExists($name)) {
            $this->stderr("Notification {$name} already exists!");
            return false;
        }

        // Make sure the directory exists
        $dir = CRAFT_BASE_PATH . '/notifications';
        if (! is_dir($dir)) {
            FileHelper::createDirectory($dir);
        }

        FileHelper::writeToFile($dir . "/{$name}.php", $this->buildClass($name));

        $this->stdout("Notification created successfully.");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     * @throws \yii\base\Exception
     */
    protected function getStub()
    {
        return Craft::$app->path->getVendorPath() . '/percipioglobal/craft-notifications/src/notification.stub';
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return file_exists(CRAFT_BASE_PATH . "/notifications/{$rawName}.php");
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     *
     * @return string
     * @throws \yii\base\Exception
     */
    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

        return str_replace('DummyClass', $name, $stub);
    }
}
