<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder\controllers;

use phpDocumentor\Reflection\Types\Resource_;
use Yii;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProxyController
 *
 * @author Simon Karlen <simi.albi@gmail.com>
 * @since 1.3
 */
class ProxyController extends Controller
{
    /**
     * Plays the proxy between js client and real file request. Prevents mixed content errors
     *
     * @param string $baseUrl
     * @param string $path
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $baseUrl, string $path): mixed
    {
        $curl = curl_init();
        $url = str_replace(' ', '%20', sprintf(
            '%s/%s',
            rtrim(StringHelper::base64UrlDecode($baseUrl), '/'),
            ltrim($path, '/')
        ));

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper(Yii::$app->request->method));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Yii2-Curl-Agent');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);
        list ($headers, $body) = $this->parseCurlResponse($curl, $response);

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

        return $body;
    }

    /**
     * Parse curl response into header and body
     * @param resource $curl
     * @param string $response
     * @return array
     */
    protected function parseCurlResponse($curl, string $response): array
    {
        $headers = [];
        $headerText = substr($response, 0, strpos($response, "\r\n\r\n"));
        $body = substr($response, curl_getinfo($curl, CURLINFO_HEADER_SIZE));

        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list($key, $value) = explode(':', $line, 2);
                $headers[$key] = trim($value);
            }
        }

        return [$headers, $body];
    }
}
