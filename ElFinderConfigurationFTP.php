<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 14:33
 */

namespace simialbi\yii2\elfinder;


class ElFinderConfigurationFTP extends ElFinderConfiguration
{
    /**
     * @var string Volume driver. Set storage engine for current root, can be one of LocalFileSystem, MySQL, FTP
     */
    public $driver = ElFinder::DRIVER_FTP;

    /**
     * @var string MySQL host name
     */
    public $host = 'localhost';

    /**
     * @var integer FTP connection port (TCP)
     */
    public $port = 21;

    /**
     * @var string FTP user name
     */
    public $user = '';

    /**
     * @var string FTP password
     */
    public $pass = '';

    /**
     * @var string Connection mode (passive or active)
     */
    public $mode = ElFinder::FTP_MODE_PASSIVE;

    /**
     * @var integer Connection timeout
     */
    public $timeout = 20;

    /**
     * @var boolean Returnvalue if file/directory owner is not equal connections user name
     */
    public $owner = true;

    /**
     * @var integer New dirs mode
     */
    public $dirMode = 0755;

    /**
     * @var integer New files mode
     */
    public $fileMode = 0644;
}