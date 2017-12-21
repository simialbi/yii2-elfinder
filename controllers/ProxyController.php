<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder\controllers;

use yii\httpclient\Client;
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
		$client = new Client([
			'baseUrl' => $baseUrl
		]);

		$method = Yii::$app->request->method;
		$data   = ($method === 'get') ? Yii::$app->request->queryParams : Yii::$app->request->bodyParams;
		if (!method_exists($client, $method)) {
			$method = 'get';
		}
		$request = $client->$method($path, $data, Yii::$app->request->headers->toArray());
		/* @var $request \yii\httpclient\Request */
		$response = $request->send();

		foreach ($response->headers->toArray() as $name => $header) {
			Yii::$app->response->headers->set($name, $header);
		}

		return $response->data;
	}
}