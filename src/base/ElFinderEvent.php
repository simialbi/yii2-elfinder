<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 15.09.2017
 * Time: 14:31
 */

namespace simialbi\yii2\elfinder\base;


use yii\base\Event;

class ElFinderEvent extends Event
{
    /**
     * @var array arugments array
     */
    public $arguments;
    /**
     * @var array result array
     */
    public $result;
    /**
     * @var string relative path from the upload target
     */
    public $path;
    /**
     * @var string file name
     */
    public $fileName;
    /**
     * @var string file tmp name
     */
    public $fileTmpName;
    /**
     * @var \elFinderVolumeDriver $volume Volume Driver Instance
     */
    public $volume;
}
