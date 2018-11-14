<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 14:23
 */

namespace simialbi\yii2\elfinder;


class ElFinderConfigurationLocalFileSystem extends ElFinderConfiguration
{
    /**
     * @var integer New dirs mode
     */
    public $dirMode = 0755;

    /**
     * @var integer New files mode
     */
    public $fileMode = 0644;

    /**
     * @var boolean Follow symbolic links
     */
    public $followSymLinks = true;

    /**
     * @var string File to be detected as a folder icon image e.g. '.favicon.png'
     */
    public $detectDirIcon = '';

    /**
     * @var array Keep timestamp at inner filesystem. Allowed values are 'copy', 'move' and 'upload'
     */
    public $keepTimestamp = [];
}