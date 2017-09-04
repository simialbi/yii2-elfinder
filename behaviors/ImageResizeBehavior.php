<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 31.08.2017
 * Time: 09:11
 */

namespace simialbi\yii2\elfinder\behaviors;


use simialbi\yii2\elfinder\ElFinder;
use yii\base\Behavior;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * ImageResizeBehavior automatically resize images to predefined maximal dimensions and reduced quality to given max
 *
 * To use ImageResizeBehavior, configure the ElFinder Component to attach this behavior.
 *
 * ```php
 * use simialbi\yii2\elfinder\behaviors;
 *
 * [
 *      'modules' => [
 *          'class' => 'simialbi\yii2\elfinder\Module',
 *          'connectionSets' => [...],
 *          'volumeBehaviors' => [
 *              'default' => [
 *                  'as image_behavior' => [
 *                      'class' => 'simialbi\yii2\elfinder\behaviors\ImageResizeBehavior',
 *                      // 'maxWidth' => 1024,
 *                      // 'maxHeight' => 1024,
 *                      // 'quality' => 95,
 *                      // 'preserveExif' => false,
 *                      // 'forceEffect' => false,
 *                      // 'targetType' => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP,
 *                      // 'offDropWith' => 8
 *                  ]
 *              ]
 *          ]
 *      ]
 * ]
 * ```
 *
 * @package simialbi\yii2\elfinder\behaviors
 * @author Simon Karlen <simi.albi@gmail.com>
 */
class ImageResizeBehavior extends Behavior {
	use BehaviorTrait;

	/**
	 * @var integer Maximal width of image
	 */
	public $maxWidth = 1024;
	/**
	 * @var integer Maximal height of image
	 */
	public $maxHeight = 1024;
	/**
	 * @var integer Reduce quality
	 */
	public $quality = 95;

	/**
	 * @var boolean Preserve EXIF data (Imagick only)
	 */
	public $preserveExif = false;

	/**
	 * @var boolean Force quality changing even if image is inside max bounds
	 */
	public $forceEffect = false;

	/**
	 * @var integer Target image formats
	 */
	public $targetType = IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP;

	/**
	 * @var integer|null To disable it if it is dropped with pressing the meta key
	 * Alt: 8, Ctrl: 4, Meta: 2, Shift: 1 - sum of each value
	 * In case of using any key, specify it as an array
	 */
	public $offDropWith = null;


	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			ElFinder::EVENT_UPLOAD_BEFORE_SAVE => 'afterUploadBeforeSave'
		];
	}

	/**
	 * @param Event $event
	 */
	public function afterUploadBeforeSave(&$event) {
//		$elfinder = $event->sender;
		$src      = ArrayHelper::getValue($event->data, 'tmpname', '');
		$volume   = ArrayHelper::getValue($event->data, 'volume');
		/* @var $elfinder \elFinder */
		/* @var $volume \elFinderVolumeDriver */

		$mime = mime_content_type($src);
		if (substr($mime, 0, 5) !== 'image') {
			return;
		}

		$srcImgInfo = getimagesize($src);
		if ($srcImgInfo === false) {
			return;
		}

		// check target image type
		$imgTypes = [
			IMAGETYPE_GIF  => IMG_GIF,
			IMAGETYPE_JPEG => IMG_JPEG,
			IMAGETYPE_PNG  => IMG_PNG,
			IMAGETYPE_BMP  => IMG_WBMP,
			IMAGETYPE_WBMP => IMG_WBMP
		];
		if (!isset($imgTypes[$srcImgInfo[2]]) || !($this->targetType & $imgTypes[$srcImgInfo[2]])) {
			return;
		}

		if ($this->forceEffect || $srcImgInfo[0] > $this->maxWidth || $srcImgInfo[1] > $this->maxHeight) {
			$zoom = $zoom = min(($this->maxWidth / $srcImgInfo[0]), ($this->maxHeight / $srcImgInfo[1]));
			$volume->imageUtil('resize', $src, [
				'width'        => round($srcImgInfo[0] * $zoom),
				'height'       => round($srcImgInfo[1] * $zoom),
				'jpgQuality'   => $this->quality,
				'preserveExif' => $this->preserveExif,
				'unenlarge'    => true
			]);
			$event->handled = true;
		}
	}
}