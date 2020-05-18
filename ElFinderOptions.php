<?php
/**
 * @package yii2-elfinder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\elfinder;


use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\base\BaseObject;

class ElFinderOptions extends BaseObject implements Arrayable
{
    use ArrayableTrait;

    /**
     * @var string Set locale. Currently only UTF-8 locales are supported. Passed to `setLocale` PHP function.
     */
    public $locale = 'en_US.UTF-8';

    /**
     * @var string elFinderVolumeDriver mime.type file path as defaults. This can be overridden in each of the volume
     * by setting the volume root mimefile. The default value '' meaning uses a file 'php/mime.type'.
     */
    public $defaultMimefile = '';

    /**
     * @var \elFinderSessionInterface Session handling wrapper class object. It must implement elFinderSessionInterface.
     */
    public $session;

    /**
     * @var string Set sessionCacheKey. PHP $_SESSION array key of elFinder caches.
     */
    public $sessionCacheKey = 'elFinderCaches';

    /**
     * @var boolean elFinder save session data as `UTF-8`. If the session storage
     * mechanism of the system does not allow `UTF-8`, and it must be set `true`.
     */
    public $base64encodeSessionData = false;

    /**
     * @var string Temp directory path for Upload. Default uses `sys_get_temp_dir()`
     */
    public $uploadTempPath = '';

    /**
     * @var string Temp directory path for temporally working files. Default uses `./.tmp` if it writable.
     */
    public $commonTempPath = './.tmp';

    /**
     * @var string Connection flag files path that connection check of current request. A file is created every
     * time an access is made to this location and it is deleted at the end of the request.
     * It is recommended to specify RAM disk such as "/dev/shm".
     */
    public $connectionFlagsPath = '';

    /**
     * @var integer Max allowed archive files size (0 - no limit)
     */
    public $maxArcFilesSize = 0;

    /**
     * @var array Root options of the network mounting volume
     * e.g.:
     * ```php
     * [
     *     'optionsNetVolumes' => [
     *         '*'   => []
     *         'ftp' => []
     *     ]
     * ]
     * ```
     */
    public $optionsNetVolumes = [];

    /**
     * @var integer Max number of limits of selectable items (0 - no limit)
     */
    public $maxTargets = 1000;

    /**
     * @var boolean Throw Error on exec()
     * `true` need `try{}` block for `$connector->run();`
     */
    public $throwErrorOnExec = false;

    /**
     * @var boolean Send debug to client.
     */
    public $debug = false;

    /**
     * @var array Configure plugin options of All volumes default value. When this config is omitted, the default
     * value which plugin has is applied.
     */
    public $plugin = [];
}
