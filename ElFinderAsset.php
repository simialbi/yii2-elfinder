<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 04.09.2017
 * Time: 16:14
 */

namespace simialbi\yii2\elfinder;

use yii\web\AssetBundle;

class ElFinderAsset extends AssetBundle {
	/**
	 * @var string the directory that contains the source asset files for this asset bundle.
	 */
	public $sourcePath = '@vendor/simialbi/yii2-elfinder/assets';

	/**
	 * @var array list of CSS files that this bundle contains.
	 */
	public $css = [
		'css/elfinder.css'
	];

	/**
	 * @var array list of bundle class names that this bundle depends on.
	 */
	public $depends = [
		'simialbi\yii2\elfinder\ElFinderPluginAsset',
		'rmrevin\yii\fontawesome\AssetBundle'
	];

	/**
	 * @var array the options to be passed to [[AssetManager::publish()]] when the asset bundle
	 * is being published.
	 */
	public $publishOptions = [
		'forceCopy' => YII_DEBUG
	];
}