<?php
/**
 * @package yii2-simialbi-base
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * ElFinder Module class
 *
 * @package simialbi\yii2\elfinder
 */
class Module extends \simialbi\yii2\base\Module
{
    /**
     * @var string the namespace that controller classes are in.
     * This namespace will be used to load controller classes by prepending it to the controller
     * class name.
     *
     * If not set, it will use the `controllers` sub-namespace under the namespace of this module.
     * For example, if the namespace of this module is `foo\bar`, then the default
     * controller namespace would be `foo\bar\controllers`.
     *
     * See also the [guide section on autoloading](guide:concept-autoloading) to learn more about
     * defining namespaces and how classes are loaded.
     */
    public $controllerNamespace = 'simialbi\yii2\elfinder\controllers';
    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'connection';

    /**
     * @var ElFinderOptions|array the main options of elfinder per instance.
     *
     * Example:
     * ```php
     * [
     *     'options' => [
     *         'default' => [
     *             'class'      => 'simialbi\yii2\elfinder\ElFinderOptions',
     *             'locale'     => 'de_DE.UTF-8',
     *             'maxTargets' => 0
     *         ]
     *     ]
     * ]
     *
     * @see https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1#main-options
     * ```
     */
    public $options = [];

    /**
     * @var array the connection sets named by array key and $roots configuration as value.
     *
     * Example:
     * ```php
     * [
     *     'default' => [
     *          [
     *              'class' => 'simialbi\yii2\elfinder\ElFinderConfiguration',
     *              'path'  => '/path/to/files/',
     *              'URL'   => 'http://localhost/to/files'
     *          ],
     *          [
     *              'class' => 'simialbi\yii2\elfinder\ElFinderConfigurationFTP',
     *              'host'  => 'localhost',
     *              'user'  => 'eluser',
     *              'pass'  => 'elpass'
     *              'path'  => '/',
     *              'URL'   => 'http://localhost/files'
     *          ]
     *     ]
     * ]
     * ```
     */
    public $connectionSets = [];

    /**
     * @var array the connection sets named by array key and $behaviors value.
     *
     * Example:
     * ```php
     * [
     *     'default' => [
     *          'as image_optimize_behavior' => [
     *              'class' => 'simialbi\yii2\elfinder\behaviors\ImageOptimizeBehavior',
     *              // 'jpegOmptimizer' => 'jpegtran -copy none -optimize -progressive -outfile {to} {from}' // optional override process
     *              // 'pngOptimizer' => 'optipng -o7 -strip all -out {to} {from}' // optional override process
     *          ],
     *          'as image_resize_behavior' => [
     *              'class' => 'simialbi\yii2\elfinder\behaviors\ImageResizeBehavior',
     *              // 'maxWidth' => 1024,
     *              // 'maxHeight' => 1024,
     *              // 'quality' => 95,
     *              // 'preserveExif' => false,
     *              // 'forceEffect' => false,
     *              // 'targetType' => IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP
     *              // 'offDropWith' => null
     *          ]
     *     ]
     * ]
     * ```
     */
    public $volumeBehaviors = [];

    /**
     * {@inheritdoc}
     * @throws \yii\base\InvalidConfigException
     * @throws \ReflectionException
     */
    public function init()
    {
        $components = [];
        foreach ($this->connectionSets as $name => $connectionSet) {
            for ($i = 0; $i < count($connectionSet); $i++) {
                $connectionSet[$i] = Yii::createObject($connectionSet[$i]);
                $connectionSet[$i]->URL = Url::to([
                        '/' . $this->id . '/proxy/index',
                        'baseUrl' => StringHelper::base64UrlEncode($connectionSet[$i]->URL)
                    ]) . '&path=';
            }

            $behaviors = ArrayHelper::getValue($this->volumeBehaviors, $name, []);
            $options = ArrayHelper::remove($this->options, $name, []);
            $config = ArrayHelper::merge($behaviors, [
                'options' => $options,
                'roots' => $connectionSet
            ]);

            $components[$name] = new ElFinder($config);
        }
        $this->setComponents($components);
        $this->registerTranslations();

        parent::init();
    }
}