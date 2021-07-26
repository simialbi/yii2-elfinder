<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace yiiunit\extensions\elfinder;

use Yii;

class BasicTests extends TestCase
{
    public function testDynamicConnectionSetCreation()
    {
        /** @var \simialbi\yii2\elfinder\Module $module */
        $module = Yii::$app->getModule('elfinder');

        $this->assertInstanceOf('\simialbi\yii2\elfinder\Module', $module);
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
