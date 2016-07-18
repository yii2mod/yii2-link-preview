<?php

namespace yii2mod\linkpreview\tests;

use yii\helpers\ArrayHelper;
use Yii;
use yii2mod\linkpreview\tests\data\Controller;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'request' => [
                    'hostInfo' => 'http://domain.com',
                    'scriptUrl' => 'index.php'
                ],
            ],
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * @param array $config controller config.
     * @return Controller controller instance.
     */
    protected function createController($config = [])
    {
        return new Controller('test', Yii::$app, $config);
    }
}