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
	public $sourcePath = '@vendor/simialbi/yii2-elfinder/assets';
	public $css = [
		'css/elfinder.css'
	];

	public $depends = [
		'simialbi\yii2\elfinder\ElFinderPluginAsset'
	];
}