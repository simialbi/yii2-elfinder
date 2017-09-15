<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 04.09.2017
 * Time: 16:27
 */

namespace simialbi\yii2\elfinder;


use yii\web\AssetBundle;

class ElFinderPluginAsset extends AssetBundle {
	/**
	 * @var string the directory that contains the source asset files for this asset bundle.
	 */
	public $sourcePath = '@vendor/studio-42/elfinder';

	/**
	 * @var array list of JavaScript files that this bundle contains.
	 */
	public $js = [
		'js/elfinder.min.js'
	];

	/**
	 * @var array list of bundle class names that this bundle depends on.
	 */
	public $depends = [
		'simialbi\yii2\elfinder\JuiAsset'
	];

	/**
	 * @var array the options to be passed to [[AssetManager::publish()]] when the asset bundle
	 * is being published.
	 */
	public $publishOptions = [
		'only' => [
			'js/'
		],
		'forceCopy' => YII_DEBUG
	];

	/**
	 * @inheritdoc
	 */
	public function init() {
		if (YII_DEBUG) {
			$this->js = [
				'js/elfinder.full.js'
			];
		}
		parent::init();
	}
}