<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder\controllers;

use linslin\yii2\curl\Curl;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Class ProxyController
 *
 * @author Simon Karlen <simi.albi@gmail.com>
 * @since 1.3
 */
class ProxyController extends Controller {
	/**
	 * Plays the proxy between js client and real file request. Prevents mixed content errors
	 *
	 * @param string $baseUrl
	 * @param string $path
	 *
	 * @return mixed
	 * @throws NotFoundHttpException
	 */
	public function actionIndex($baseUrl, $path) {
		$curl = new Curl();
		$url  = str_replace(' ', '%20', sprintf(
			'%s/%s',
			rtrim(StringHelper::base64UrlDecode($baseUrl), '/'),
			ltrim($path, '/')
		));

		$method = Yii::$app->request->method;
		if (!method_exists($curl, $method)) {
			$method = 'get';
		}

		$curl->setOption(CURLOPT_FOLLOWLOCATION, true);

		$response = $curl->$method($url);
		$headers  = $curl->responseHeaders;

		if (false === $response) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		foreach ($headers as $header => $value) {
			if (strcasecmp('content-type', $header) === 0) {
				Yii::$app->response->headers
					->set('Content-Type', $value)
					->set('Content-Transfer-Encoding', 'binary');
				break;
			}
		}

		Yii::$app->response->format = Response::FORMAT_RAW;

		return $response;
	}
}