<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 16:25
 */

namespace simialbi\yii2\elfinder\controllers;

use simialbi\yii2\elfinder\ElFinder;
use simialbi\yii2\elfinder\Module;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class ConnectionController
 * @package simialbi\yii2\elfinder\controllers
 *
 * @property Module $module
 */
class ConnectionController extends Controller {
	/**
	 * @param string $name
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionIndex($name) {
		$roots     = ArrayHelper::getValue($this->module->connectionSets, $name);
		$behaviors = ArrayHelper::getValue($this->module->volumeBehaviors, $name);

		if (is_null($roots) || !is_array($roots)) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		for ($i = 0; $i < count($roots); $i++) {
			$roots[$i] = Yii::createObject($roots[$i]);
		}

		$elFinder  = new ElFinder([
			'roots'     => $roots,
			'behaviors' => $behaviors
		]);
		$connector = new \elFinderConnector($elFinder->getApi());

		$connector->run();
	}
}