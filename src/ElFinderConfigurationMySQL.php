<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 14:28
 */

namespace simialbi\yii2\elfinder;

class ElFinderConfigurationMySQL extends ElFinderConfiguration
{
    /**
     * @var string Volume driver. Set storage engine for current root, can be one of LocalFileSystem, MySQL, FTP
     */
    public string $driver = ElFinder::DRIVER_MYSQL;

    /**
     * @var string MySQL host name
     */
    public string $host = 'localhost';

    /**
     * @var string|null Path to MySQL sock file
     */
    public ?string $socket = null;

    /**
     * @var integer|null MySQL connection port (TCP)
     */
    public ?int $port = null;

    /**
     * @var string MySQL user name
     */
    public string $user = '';

    /**
     * @var string MySQL password
     */
    public string $pass = '';

    /**
     * @var string MySQL database name
     */
    public string $db = '';

    /**
     * @var string MySQL files table name
     */
    public string $files_table = 'elfinder_file';
}
