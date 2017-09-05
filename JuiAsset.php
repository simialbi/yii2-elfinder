<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 05.09.2017
 * Time: 08:25
 */

namespace simialbi\yii2\elfinder;


use yii\web\AssetBundle;

class JuiAsset extends AssetBundle {
	public $sourcePath = '@bower/jquery-ui';
	public $js = [
		'jquery-ui.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}