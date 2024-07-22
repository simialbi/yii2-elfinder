<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace yiiunit\extensions\elfinder;

use Yii;
use yii\base\Action;
use yii\base\Module;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    /**
     * @param array $config
     * @param string $appClass
     */
    protected function mockWebApplication(array $config = [], string $appClass = '\yii\web\Application'): void
    {
        $app = new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'modules' => [
                'elfinder' => [
                    'class' => 'simialbi\yii2\elfinder\Module',
                    'options' => [],
                    'connectionSets' => [
                        'default' => [
                            [
                                'class' => 'simialbi\yii2\elfinder\ElFinderConfigurationLocalFileSystem',
                                'path' => '@webroot/default',
                                'URL' => '@web/default'
                            ]
                        ]
                    ],
                    'volumeBehaviors' => [
                        'default' => [
                            'as resizer' => [
                                'class' => 'simialbi\yii2\elfinder\behaviors\ImageResizeBehavior',
                                'maxWidth' => 1920,
                                'maxHeight' => 1080,
                                'quality' => 70
                            ]
                        ]
                    ]
                ]
            ],
            'aliases' => [
                '@bower' => '@vendor/bower-asset',
                '@npm' => '@vendor/npm-asset',
            ],
            'components' => [
                'request' => [
                    'cookieValidationKey' => '2VYuNNIognPSVv0zqj1C9sdmgk_O1UBa',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php'
                ],
                'log' => [
                    'traceLevel' => YII_DEBUG ? 3 : 0,
                    'targets' => [
                        [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error', 'warning', 'info'],
                        ]
                    ]
                ],
                'urlManager' => [
                    'showScriptName' => true,
                ]
            ],
            'params' => [
                'adminEmail' => 'admin@example.com',
            ]
        ], $config));
    }

    /**
     * Mocks controller action with parameters
     *
     * @param string $controllerId
     * @param string $actionID
     * @param string|null $moduleID
     * @param array $params
     */
    protected function mockAction(string $controllerId, string $actionID, ?string $moduleID = null, array $params = []): void
    {
        Yii::$app->controller = $controller = new Controller($controllerId, Yii::$app);
        $controller->actionParams = $params;
        $controller->action = new Action($actionID, $controller);

        if ($moduleID !== null) {
            $controller->module = new Module($moduleID);
        }
    }

    /**
     * Removes controller
     */
    protected function removeMockedAction(): void
    {
        Yii::$app->controller = null;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication(): void
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Asserting two strings equality ignoring line endings
     *
     * @param string $expected
     * @param string $actual
     */
    public function assertEqualsWithoutLE(string $expected, string $actual): void
    {
        $expected = str_replace("\r\n", "\n", $expected);
        $actual = str_replace("\r\n", "\n", $actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Asserting two strings equality ignoring line endings
     *
     * @param string $needle
     * @param string $haystack
     */
    public function assertContainsWithoutLE(string $needle, string $haystack): void
    {
        $needle = str_replace("\r\n", "\n", $needle);
        $haystack = str_replace("\r\n", "\n", $haystack);

        $this->assertContains($needle, $haystack);
    }
}
