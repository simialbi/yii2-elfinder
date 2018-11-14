<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 11:35
 */

namespace simialbi\yii2\elfinder;


use simialbi\yii2\elfinder\elfinder\ElFinder as ElFinderApi;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ElFinder extends Component
{
    /**
     * Boxdrive driver
     */
    const DRIVER_BOXDRIVE = 'Box';
    /**
     * Dropbox driver
     */
    const DRIVER_DROPBOX = 'Dropbox';
    /**
     * Dropbox2 driver
     */
    const DRIVER_DROPBOX2 = 'Dropbox2';
    /**
     * FTP driver
     */
    const DRIVER_FTP = 'FTP';
    /**
     * GoogleDrive driver
     */
    const DRIVER_GOOGLE_DRIVE = 'GoogleDrive';
    /**
     * Group driver
     */
    const DRIVER_GOUP = 'Group';
    /**
     * LocalFileSystem driver
     */
    const DRIVER_LOCAL_FILE_SYSTEM = 'LocalFileSystem';
    /**
     * MySQL driver
     */
    const DRIVER_MYSQL = 'MySQL';
    /**
     * OneDrive driver
     */
    const DRIVER_ONEDRIVE = 'OneDrive';
    /**
     * Trash driver
     */
    const DRIVER_TRASH = 'Trash';

    /**
     * Automatic mime detection
     */
    const MIME_DETECT_AUTO = 'auto';
    /**
     * Internal mime detection
     */
    const MIME_DETECT_INTERNAL = 'internal';
    /**
     * Mime detection via finfo methods/class
     */
    const MIME_DETECT_FINFO = 'finfo';
    /**
     * Mime detection via mime_content_type functions
     */
    const MIME_DETECT_MIME = 'mime_content_type';

    /**
     * Automatically detect image library
     */
    const IMG_LIB_AUTO = 'auto';
    /**
     * Imagick image library
     */
    const IMG_LIB_IMAGICK = 'imagick';
    /**
     * GD image library
     */
    const IMG_LIB_GD = 'gd';
    /**
     * Convert image library
     */
    const IMG_LIB_CONVERT = 'convert';

    /**
     * Command "open"
     * open directory and initializes data when no directory is defined
     */
    const CMD_OPEN = 'open';
    /**
     * Command "file"
     * output file contents to the browser (download/preview)
     */
    const CMD_FILE = 'file';
    /**
     * Command "tree"
     * return child directories
     */
    const CMD_TREE = 'tree';
    /**
     * Command "parents"
     * return parent directories and its subdirectory childs
     */
    const CMD_PARENTS = 'parents';
    /**
     * Command "ls"
     * list of items name in directory
     */
    const CMD_LS = 'ls';
    /**
     * Command "tmb"
     * create thumbnails for selected files
     */
    const CMD_TMB = 'tmb';
    /**
     * Command "size"
     * return size for selected files or total folder(s) size
     */
    const CMD_SIZE = 'size';
    /**
     * Command "dim"
     * return image dimensions
     */
    const CMD_DIM = 'dim';
    /**
     * Command "mkdir"
     * create directory
     */
    const CMD_MKDIR = 'mkdir';
    /**
     * Command "mkfile"
     * create text file
     */
    const CMD_MKFILE = 'mkfile';
    /**
     * Command "rm"
     * delete files/directories
     */
    const CMD_RM = 'rm';
    /**
     * Command "rename"
     * rename file
     */
    const CMD_RENAME = 'rename';
    /**
     * Command "duplicate"
     * create copy of file
     */
    const CMD_DUPLICATE = 'duplicate';
    /**
     * Command "paste"
     * copy or move files
     */
    const CMD_PASTE = 'paste';
    /**
     * Command "upload"
     * upload file
     */
    const CMD_UPLOAD = 'upload';
    /**
     * Command "get"
     * output plain/text file contents (preview)
     */
    const CMD_GET = 'get';
    /**
     * Command "put"
     * save text file content
     */
    const CMD_PUT = 'put';
    /**
     * Command "archive"
     * create archive
     */
    const CMD_ARCHIVE = 'archive';
    /**
     * Command "extract"
     * extract archive
     */
    const CMD_EXTRACT = 'extract';
    /**
     * Command "search"
     * search for files
     */
    const CMD_SEARCH = 'search';
    /**
     * Command "info"
     * return info for files. (used by client "places" ui)
     */
    const CMD_INFO = 'info';
    /**
     * Command "resize"
     * modify image file (resize/crop/rotate)
     */
    const CMD_RESIZE = 'resize';
    /**
     * Command "url"
     * return file url
     */
    const CMD_URL = 'url';
    /**
     * Command "netmount"
     * mount network volume during user session. Only ftp now supported
     */
    const CMD_NETMOUNT = 'netmount';
    /**
     * Command "zipdl"
     * download multiple items by archive (API >= 2.1012)
     */
    const CMD_ZIPDL = 'zipdl';
    /**
     * Command "callback"
     * Output callback result
     */
    const CMD_CALLBACK = 'callback';
    /**
     * Command "chmod"
     * chmod items
     */
    const CMD_CHMOD = 'chmod';

    /**
     * After upload before save event
     */
    const EVENT_UPLOAD_BEFORE_SAVE = 'upload.presave';
    /**
     * Before and after "open" event
     */
    const EVENT_BEFORE_OPEN = 'beforeOpen';
    const EVENT_AFTER_OPEN = 'afterOpen';
    /**
     * Before and after "file" event
     */
    const EVENT_BEFORE_FILE = 'beforeFile';
    const EVENT_AFTER_FILE = 'afterFile';
    /**
     * Before and after "tree" event
     */
    const EVENT_BEFORE_TREE = 'beforeTree';
    const EVENT_AFTER_TREE = 'afterTree';
    /**
     * Before and after "parents" event
     */
    const EVENT_BEFORE_PARENTS = 'beforeParents';
    const EVENT_AFTER_PARENTS = 'afterParents';
    /**
     * Before and after "ls" event
     */
    const EVENT_BEFORE_LS = 'beforeLs';
    const EVENT_AFTER_LS = 'afterLs';
    /**
     * Before and after "tmb" event
     */
    const EVENT_BEFORE_TMB = 'beforeTmb';
    const EVENT_AFTER_TMB = 'afterTmb';
    /**
     * Before and after "size" event
     */
    const EVENT_BEFORE_SIZE = 'beforeSize';
    const EVENT_AFTER_SIZE = 'afterSize';
    /**
     * Before and after "dim" event
     */
    const EVENT_BEFORE_DIM = 'beforeDim';
    const EVENT_AFTER_DIM = 'afterDim';
    /**
     * Before and after "mkdir" event
     */
    const EVENT_BEFORE_MKDIR = 'beforeMkdir';
    const EVENT_AFTER_MKDIR = 'afterMkdir';
    /**
     * Before and after "mkfile" event
     */
    const EVENT_BEFORE_MKFILE = 'beforeMkfile';
    const EVENT_AFTER_MKFILE = 'afterMkfile';
    /**
     * Before and after "rm" event
     */
    const EVENT_BEFORE_RM = 'beforeRm';
    const EVENT_AFTER_RM = 'afterRm';
    /**
     * Before and after "rename" event
     */
    const EVENT_BEFORE_RENAME = 'beforeRename';
    const EVENT_AFTER_RENAME = 'afterRename';
    /**
     * Before and after "duplicate" event
     */
    const EVENT_BEFORE_DUPLICATE = 'beforeDuplicate';
    const EVENT_AFTER_DUPLICATE = 'afterDuplicate';
    /**
     * Before and after "paste" event
     */
    const EVENT_BEFORE_PASTE = 'beforePaste';
    const EVENT_AFTER_PASTE = 'afterPaste';
    /**
     * Before and after "upload" event
     */
    const EVENT_BEFORE_UPLOAD = 'beforeUpload';
    const EVENT_AFTER_UPLOAD = 'afterUpload';
    /**
     * Before and after "get" event
     */
    const EVENT_BEFORE_GET = 'beforeGet';
    const EVENT_AFTER_GET = 'afterGet';
    /**
     * Before and after "put" event
     */
    const EVENT_BEFORE_PUT = 'beforePut';
    const EVENT_AFTER_PUT = 'afterPut';
    /**
     * Before and after "archive" event
     */
    const EVENT_BEFORE_ARCHIVE = 'beforeArchive';
    const EVENT_AFTER_ARCHIVE = 'afterArchive';
    /**
     * Before and after "extract" event
     */
    const EVENT_BEFORE_EXTRACT = 'beforeExtract';
    const EVENT_AFTER_EXTRACT = 'afterExtract';
    /**
     * Before and after "search" event
     */
    const EVENT_BEFORE_SEARCH = 'beforeSearch';
    const EVENT_AFTER_SEARCH = 'afterSearch';
    /**
     * Before and after "info" event
     */
    const EVENT_BEFORE_INFO = 'beforeInfo';
    const EVENT_AFTER_INFO = 'afterInfo';
    /**
     * Before and after "resize" event
     */
    const EVENT_BEFORE_RESIZE = 'beforereSize';
    const EVENT_AFTER_RESIZE = 'afterreSize';
    /**
     * Before and after "url" event
     */
    const EVENT_BEFORE_URL = 'beforeUrl';
    const EVENT_AFTER_URL = 'afterUrl';
    /**
     * Before and after "netmount" event
     */
    const EVENT_BEFORE_NETMOUNT = 'beforeNetmount';
    const EVENT_AFTER_NETMOUNT = 'afterNetmount';
    /**
     * Before and after "zipdl" event
     */
    const EVENT_BEFORE_ZIPDL = 'beforeZipdl';
    const EVENT_AFTER_ZIPDL = 'afterZipdl';
    /**
     * Before and after "callback" event
     */
    const EVENT_BEFORE_CALLBACK = 'beforeCallback';
    const EVENT_AFTER_CALLBACK = 'afterCallback';
    /**
     * Before and after "chmod" event
     */
    const EVENT_BEFORE_CHMOD = 'beforeChmod';
    const EVENT_AFTER_CHMOD = 'afterChmod';

    const FTP_MODE_ACTIVE = 'active';
    const FTP_MODE_PASSIVE = 'passive';

    /**
     * @var ElFinderOptions the main options of elfinder
     */
    public $options = [];

    /**
     * @var ElFinderConfiguration[] Array of arrays with per root settings. This is the only required option.
     */
    public $roots = [];

    /**
     * @var \elFinder api instance
     */
    protected $_elfinder;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->roots)) {
            throw new InvalidConfigException('roots parameter must be set');
        }

        parent::init();
    }

    /**
     * Validates and fetches the elFinder API instance
     *
     * @return \elFinder
     */
    public function getApi()
    {
        if (empty($this->_elfinder) || !$this->_elfinder instanceof \elFinder) {
            $this->setApi();
        }

        return $this->_elfinder;
    }

    /**
     * Sets the elFinder API instance
     */
    public function setApi()
    {
        foreach ($this->roots as &$root) {
            $root->path = Yii::getAlias($root->path);
            $root->tmbPath = Yii::getAlias($root->tmbPath);
            $root->tmpPath = Yii::getAlias($root->tmpPath);
            $root->tmbURL = Yii::getAlias($root->tmbURL);
            $root->URL = Yii::getAlias($root->URL);
            $root->quarantine = Yii::getAlias($root->quarantine);
        }
        $roots = ArrayHelper::toArray($this->roots);

        $this->_elfinder = new ElFinderApi(ArrayHelper::merge(ArrayHelper::toArray($this->options), [
            'roots' => $roots
        ]));

        $afterEvents = [
            self::CMD_ARCHIVE,
            self::CMD_CALLBACK,
            self::CMD_CHMOD,
            self::CMD_DIM,
            self::CMD_DUPLICATE,
            self::CMD_EXTRACT,
            self::CMD_FILE,
            self::CMD_GET,
            self::CMD_INFO,
            self::CMD_LS,
            self::CMD_MKDIR,
            self::CMD_MKFILE,
            self::CMD_NETMOUNT,
            self::CMD_OPEN,
            self::CMD_PARENTS,
            self::CMD_PASTE,
            self::CMD_PUT,
            self::CMD_RENAME,
            self::CMD_RESIZE,
            self::CMD_RM,
            self::CMD_SEARCH,
            self::CMD_SIZE,
            self::CMD_TMB,
            self::CMD_TREE,
            self::CMD_UPLOAD,
            self::CMD_URL,
            self::CMD_ZIPDL
        ];
        $beforeEvents = array_map(function ($item) {
            return $item . '.pre';
        }, $afterEvents);

        $this->_elfinder->bind(implode(' ', $beforeEvents), [$this, 'handleApiBeforeEvent']);
        $this->_elfinder->bind(implode(' ', $afterEvents), [$this, 'handleApiAfterEvent']);
        $this->_elfinder->bind(self::EVENT_UPLOAD_BEFORE_SAVE, [$this, 'handleApiUploadBeforeSave']);
    }

    /**
     * Handles elfinder api before events
     *
     * @param string $cmd command name
     * @param array $args arugments array
     * @param \elFinder $instance elFinder instance
     * @param \elFinderVolumeDriver $dstVolume Volume Driver instance
     *
     * @return boolean
     * @throws InvalidConfigException
     */
    public function handleApiBeforeEvent($cmd, &$args, $instance, $dstVolume)
    {
        $event = Yii::createObject([
            'class' => 'simialbi\yii2\elfinder\base\ElFinderEvent',
            'name' => self::EVENT_UPLOAD_BEFORE_SAVE,
            'sender' => $instance,
            'arguments' => $args,
            'volume' => $dstVolume
        ]);
        /* @var $event \simialbi\yii2\elfinder\base\ElFinderEvent */

        $this->trigger('before' . ucfirst(strtolower($cmd)), $event);

        return $event->handled;
    }

    /**
     * Handles elfinder api after events
     *
     * @param string $cmd command name
     * @param array $result result array
     * @param array $args arugments array
     * @param \elFinder $instance elFinder instance
     * @param \elFinderVolumeDriver $dstVolume Volume Driver instance
     *
     * @return boolean
     * @throws InvalidConfigException
     */
    public function handleApiAfterEvent($cmd, &$result, $args, $instance, $dstVolume)
    {
        $event = Yii::createObject([
            'class' => 'simialbi\yii2\elfinder\base\ElFinderEvent',
            'name' => self::EVENT_UPLOAD_BEFORE_SAVE,
            'sender' => $instance,
            'arguments' => $args,
            'result' => $result,
            'volume' => $dstVolume
        ]);
        /* @var $event \simialbi\yii2\elfinder\base\ElFinderEvent */

        $this->trigger('after' . ucfirst(strtolower($cmd)), $event);

        return $event->handled;
    }

    /**
     * Handles elfinder api after events
     *
     * @param string $path relative path from the upload target
     * @param string $name file name
     * @param string $tmpname file tmp name
     * @param \elFinder $instance elFinder instance
     * @param \elFinderVolumeDriver $dstVolume Volume Driver instance
     *
     * @return boolean
     * @throws InvalidConfigException
     */
    public function handleApiUploadBeforeSave(&$path, &$name, $tmpname, $instance, $dstVolume)
    {
        $event = Yii::createObject([
            'class' => 'simialbi\yii2\elfinder\base\ElFinderEvent',
            'name' => self::EVENT_UPLOAD_BEFORE_SAVE,
            'sender' => $instance,
            'path' => $path,
            'fileName' => $name,
            'fileTmpName' => $tmpname,
            'volume' => $dstVolume
        ]);
        /* @var $event \simialbi\yii2\elfinder\base\ElFinderEvent */

        $this->trigger(self::EVENT_UPLOAD_BEFORE_SAVE, $event);

        return $event->handled;
    }
}