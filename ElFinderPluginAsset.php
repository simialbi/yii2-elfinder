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
	public $sourcePath = '@vendor/studio-42/elfinder';
	public $js = [
		'js/elfinder.min.js'
	];

	public $depends = [
		'yii\jui\JuiAsset'
	];
}