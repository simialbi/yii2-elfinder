<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 11:49
 */

namespace simialbi\yii2\elfinder;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\base\BaseObject;

class ElFinderConfiguration extends BaseObject implements Arrayable
{
    use ArrayableTrait;

    /**
     * @var string Volume driver. Set storage engine for current root, can be one of LocalFileSystem, MySQL, FTP
     */
    public $driver = ElFinder::DRIVER_LOCAL_FILE_SYSTEM;

    /**
     * @var boolean It must set true If volume driver supports autoload function.
     */
    public $autoload = true;

    /**
     * @var string Folder hash value on elFinder to be the parent of this volume
     */
    public $phash = '';

    /**
     * @var string Folder hash value on elFinder to trash bin of this volume, it require 'copyJoin' to true
     */
    public $trashHash = '';

    /**
     * @var string Root directory path
     */
    public $path = '/';

    /**
     * @var string Open this path on initial request instead of root path
     *
     * Notice: In order to validate this option by a multi-route, you have to set a value only to the volume which
     * applies this option.
     */
    public $startPath = '';

    /**
     * @var string URL that points to path directory (also called 'root URL'). If not set client will not see full path
     * to files (replacement for old fileURL option), also all files downloads will be handled by connector.
     *
     * Disable real file path from being shown
     */
    public $URL = '';

    /**
     * @var string This volume's local encoding. (server's file system encoding) It's necessary to be valid value to
     * iconv.
     */
    public $encoding = '';

    /**
     * @var string This volume's local locale. It's important for encoding setting. It's necessary to be valid value in
     * your server.　(It can be checked by showlocale.php or typing locale -a on your server shell.)
     */
    public $locale = '';

    /**
     * @var string Root path alias for volume root. If not set will use directory name of path.
     * Warning: when this option is set it will also rewrite return path for getFileCallback
     */
    public $alias = '';

    /**
     * @var boolean Enable i18n folder name that convert name to elFinderInstance.messages['folder_'+name]
     */
    public $i18nFolderName = false;

    /**
     * @var string Method to detect files mimetypes. Can be auto, internal, finfo, mime_content_type
     */
    public $mimeDetect = ElFinder::MIME_DETECT_AUTO;

    /**
     * @var string Path to alternative mime types file. Only used when mimeDetect set to internal. If not set will use
     * default mime.types
     */
    public $mimefile = '';

    /**
     * @var array Additional Mime type normalization map
     */
    public $additionalMimeMap = [];

    /**
     * @var string MIME regex of send HTTP header "Content-Disposition: inline" on file open command.
     *
     * '.*' is allow inline of all of MIME types
     * '$^' is not allow inline of all of MIME types
     * '^(?:image|text/plain$)' is recommended for safety on public elFinder
     */
    public $dispInlineRegex = '^(?:(?:image|text)|application/x-shockwave-flash$)';

    /**
     * @var string Image manipulations library. Can be auto, imagick, gd or convert
     */
    public $imgLib = ElFinder::IMG_LIB_AUTO;

    /**
     * @var string Directory for thumbnails. If this is a simple filename, it will be prefixed with the root directory
     * path. If you choose to use a location outside of the root directory, you should use a full pathname as a relative
     * path using ellipses will get mangled and may not work (create thumbnails because tmbPath is NOT writable)
     * on some server OS's.
     */
    public $tmbPath = '.tmb';

    /**
     * @var integer Umask for thumbnails dir creation. Will be removed in future
     */
    public $tmbPathMode = 0777;

    /**
     * @var string URL for thumbnails directory set in tmbPath. Set it only if you want to store thumbnails outside root
     * directory.
     *
     * If you want chose original image as thumbnail it is able to set 'self'.
     */
    public $tmbURL = '';

    /**
     * @var integer Thumbnails size in pixels. Thumbnails are square
     */
    public $tmbSize = 48;

    /**
     * @var boolean Crop thumbnails to fit tmbSize. true - resize and crop, false - scale image to fit thumbnail size
     */
    public $tmbCrop = true;

    /**
     * @var string Thumbnails background color (hex #rrggbb or transparent)
     */
    public $tmbBgColor = 'transparent';

    /**
     * @var string Image rotate fallback background color (hex #rrggbb). Uses this color if it can not specify to
     * transparent.
     */
    public $bgColorFb = '#ffffff';

    /**
     * @var boolean Fallback self image to thumbnail (nothing imgLib).
     */
    public $tmbFbSelf = true;

    /**
     * @var boolean Replace files on paste or give new names to pasted files. true - old file will be replaced with new
     * one, false - new file get name - original_name-number.ext
     */
    public $copyOverwrite = true;

    /**
     * @var boolean Merge new and old content on paste. true - join new and old directories content, false - replace old
     * directories with new ones
     */
    public $copyJoin = true;

    /**
     * @var boolean Allow to copy from this volume to other ones
     */
    public $copyFrom = true;

    /**
     * @var boolean Allow to copy from other volumes to this one
     */
    public $copyTo = true;

    /**
     * @var string Temporary directory used for extracts etc. The default tmpPath is to use 'tmbPath'. If you choose to
     * use another location, set 'tmpPath' to a full pathname.
     */
    public $tmpPath = '';

    /**
     * @var boolean Replace files with the same name on upload or give them new names. true - replace old files, false
     * give new names like original_name-number.ext
     */
    public $uploadOverwrite = true;

    /**
     * @var array Mimetypes allowed to upload
     */
    public $uploadAllow = [];

    /**
     * @var array Mimetypes not allowed to upload. Same values accepted as in uploadAllow
     */
    public $uploadDeny = [];

    /**
     * @var array Order to proccess uploadAllow and uploadDeny options. Logic is the same as Apache web server options
     * Allow, Deny, Order
     */
    public $uploadOrder = ['deny', 'allow'];

    /**
     * @var integer|string Maximum upload file size. This size is per files. Can be set as number or string with unit
     * 10M, 500K, 1G. Note: elFinder 2.1+ support chunked file uploading. 0 means unlimited upload.
     */
    public $uploadMaxSize = 0;

    /**
     * @var integer Maximum number of connection of chunked file uploading. -1 to disable chunked file upload.
     */
    public $uploadMaxConn = 3;

    /**
     * @var array Default file/directory permissions. Setting hidden, locked here - take no effect
     */
    public $defaults = ['read' => true, 'write' => true];

    /**
     * @var array File permission attributes.
     */
    public $attributes = [];

    /**
     * @var string|callable Validate new file name regex or function
     */
    public $acceptedName = '/^[^\.].*/';

    /**
     * @var callable|null Function or class instance method to control files permissions. This works similar to bind
     * option
     */
    public $accessControl = null;

    /**
     * @var mixed Data that will be passed to access control method
     */
    public $accessControlData = null;

    /**
     * @var array List of commands disabled on this root
     */
    public $disabled = [];

    /**
     * @var boolean Include file owner, group & mode in stat results on supported volume driver
     * (LocalFileSystem(require POSIX in PHP), FTP on UNIX system-like). false to inactivate "chmod" command.
     */
    public $statOwner = false;

    /**
     * @var boolean Allow exec chmod of read-only( on elFinder permission ) files.
     */
    public $allowChmodReadOnly = false;

    /**
     * @var integer How many subdirs levels return per request
     */
    public $treeDeep = 1;

    /**
     * @var boolean|integer Check children directories for other directories in it. true every folder will be check for
     * children folders, -1 every folder will be check asynchronously, false all folders will be marked as having
     * subfolders
     */
    public $checkSubfolders = true;

    /**
     * @var string Directory separator. Required by client to show correct file paths
     */
    public $separator = DIRECTORY_SEPARATOR;

    /**
     * @var string File modification date format. This value is passed to PHP date() function
     */
    public $dateFormat = 'j M Y H:i';

    /**
     * @var string File modification time format
     */
    public $timeFormat = 'H:i';

//	public $cryptLib = null;

    /**
     * @var array Allowed archive's mimetypes to create. Leave empty for all available types
     */
    public $archiveMimes = [];

    /**
     * @var array Manual config for archivers. Leave empty for auto detect
     */
    public $archivers = [];

    /**
     * @var string Temporary directory for archive file extracting. This option only uses the LocalFileSystem volume driver.
     *
     * We recommend to set a path outside the document root.
     */
    public $quarantine = '.quarantine';

    /**
     * @var array Configure plugin options of each volume
     */
    public $plugin = [];
}