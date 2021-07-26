<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 04.09.2017
 * Time: 10:55
 */

namespace simialbi\yii2\elfinder\behaviors;


use simialbi\yii2\elfinder\base\ElFinderEvent;
use simialbi\yii2\elfinder\ElFinder;
use yii\base\Behavior;

/**
 * ImageRotateBehavior automatically rotates jpeg images with exif data to correct position on upload.
 *
 * To use ImageRotateBehavior, configure the ElFinder Component to attach this behavior.
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
 *                      'class' => 'simialbi\yii2\elfinder\behaviors\ImageRotateBehavior',
 *                      // 'quality' => 95, // optional override
 *                      // 'offDropWidth' => 8 // optional override
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
class ImageRotateBehavior extends Behavior
{
    use BehaviorTrait;

    /**
     * @var integer Reduce quality
     */
    public $quality = 95;

    /**
     * @var integer|null To disable it if it is dropped with pressing the meta key
     * Alt: 8, Ctrl: 4, Meta: 2, Shift: 1 - sum of each value
     * In case of using any key, specify it as an array
     */
    public $offDropWidth = null;


    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ElFinder::EVENT_UPLOAD_BEFORE_SAVE => 'afterUploadBeforeSave'
        ];
    }

    /**
     * @param ElFinderEvent $event
     */
    public function afterUploadBeforeSave($event)
    {
//      $elfinder = $event->sender;
        $src = $event->fileTmpName;
        $volume = $event->volume;
        /* @var $elfinder \elFinder */
        /* @var $volume \elFinderVolumeDriver */

        if (function_exists('mime_content_type')) {
            $mime = mime_content_type($src);
            if (substr($mime, 0, 5) !== 'image') {
                return;
            }
        }

        $srcImgInfo = getimagesize($src);
        if ($srcImgInfo === false) {
            return;
        }

        // check target image type
        if ($srcImgInfo[2] !== IMAGETYPE_JPEG) {
            return;
        }

        if (!function_exists('exif_read_data')) {
            return;
        }
        $degree = 0;
        $exif = exif_read_data($src);
        if (!$exif || empty($exif['Orientation'])) {
            return;
        }
        switch ($exif['Orientation']) {
            case 8:
                $degree = 270;
                break;
            case 3:
                $degree = 180;
                break;
            case 6:
                $degree = 90;
                break;
        }
        $volume->imageUtil('rotate', $src, [
            'degree' => $degree,
            'jpgQuality' => $this->quality
        ]);
    }
}
