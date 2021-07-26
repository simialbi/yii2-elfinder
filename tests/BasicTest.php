<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace yiiunit\extensions\elfinder;

use Yii;

class BasicTest extends TestCase
{
    public function testBasicConfiguration()
    {
        /** @var \simialbi\yii2\elfinder\Module $module */
        $module = Yii::$app->getModule('elfinder');

        $this->assertInstanceOf('\simialbi\yii2\elfinder\Module', $module);
        $this->assertArrayHasKey('default', $module->components);
        /** @var \simialbi\yii2\elfinder\ElFinder $default */
        $default = $module->components['default'];
        $this->assertInstanceOf('\simialbi\yii2\elfinder\ElFinder', $default);
        $this->assertArrayHasKey('default', $module->volumeBehaviors);
        $this->assertArrayHasKey('resizer', $default->getBehaviors());
        $this->assertInstanceOf('\simialbi\yii2\elfinder\behaviors\ImageResizeBehavior', $default->getBehaviors()['resizer']);
    }

    public function testDynamicConnectionSetCreation()
    {
        /** @var \simialbi\yii2\elfinder\Module $module */
        $module = Yii::$app->getModule('elfinder');


        $configuration = [
            'class' => 'simialbi\yii2\elfinder\ElFinderConfigurationLocalFileSystem',
            'path' => '@webroot/test/2',
            'URL' => '@web/test/2'
        ];
        $module->addConnectionSet('test', $configuration);

        $this->assertArrayHasKey('test', $module->components);
        $this->assertInstanceOf('simialbi\yii2\elfinder\ElFinder', $module->components['test']);
    }
}
