<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder\controllers;

use linslin\yii2\curl\Curl;
use yii\helpers\StringHelper;
use yii\web\Controller;
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
	 */
	public function actionIndex($baseUrl, $path) {
		$curl = new Curl();
		$url  = rtrim(StringHelper::base64UrlDecode($baseUrl), '/').'/'.ltrim($path, '/');

		$method = Yii::$app->request->method;
		if (!method_exists($curl, $method)) {
			$method = 'get';
		}

		$response = $curl->$method($url);
		$headers  = $curl->responseHeaders;

		foreach ($headers as $header => $value) {
			if (strcasecmp('content-type', $header) === 0) {
				Yii::$app->response->headers->set('Content-Type', $value);
			}
		}

		return $response;
	}
}