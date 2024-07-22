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
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ConnectionController
 * @package simialbi\yii2\elfinder\controllers
 *
 * @property Module $module
 */
class ConnectionController extends Controller
{
    /**
     * @param string $instanceName
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $instanceName = 'default'): void
    {
        $elFinder = ArrayHelper::getValue($this->module->components, $instanceName);

        if (!($elFinder instanceof ElFinder)) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        $connector = new \elFinderConnector($elFinder->getApi());
        $connector->run();
    }
}
