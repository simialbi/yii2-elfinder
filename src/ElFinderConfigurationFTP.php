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
    public string $driver = ElFinder::DRIVER_FTP;

    /**
     * @var string MySQL host name
     */
    public string $host = 'localhost';

    /**
     * @var integer FTP connection port (TCP)
     */
    public int $port = 21;

    /**
     * @var string FTP user name
     */
    public string $user = '';

    /**
     * @var string FTP password
     */
    public string $pass = '';

    /**
     * @var string Connection mode (passive or active)
     */
    public string $mode = ElFinder::FTP_MODE_PASSIVE;

    /**
     * @var integer Connection timeout
     */
    public int $timeout = 20;

    /**
     * @var boolean Returnvalue if file/directory owner is not equal connections user name
     */
    public bool $owner = true;

    /**
     * @var integer New dirs mode
     */
    public int $dirMode = 0755;

    /**
     * @var integer New files mode
     */
    public int $fileMode = 0644;
}
