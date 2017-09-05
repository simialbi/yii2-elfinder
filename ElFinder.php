<?php
/**
 * Created by PhpStorm.
 * User: karlen
 * Date: 30.08.2017
 * Time: 11:35
 */

namespace simialbi\yii2\elfinder;


use simialbi\yii2\elfinder\elfinder\ElFinder as ElFinderApi;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use Yii;

class ElFinder extends Component {
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

	const FTP_MODE_ACTIVE = 'active';
	const FTP_MODE_PASSIVE = 'passive';

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
	 */
	public function init() {
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
	public function getApi() {
		if (empty($this->_elfinder) || !$this->_elfinder instanceof \elFinder) {
			$this->setApi();
		}

		return $this->_elfinder;
	}

	/**
	 * Sets the elFinder API instance
	 */
	public function setApi() {
		foreach ($this->roots as &$root) {
			$root->path       = Yii::getAlias($root->path);
			$root->tmbPath    = Yii::getAlias($root->tmbPath);
			$root->tmpPath    = Yii::getAlias($root->tmpPath);
			$root->tmbURL     = Yii::getAlias($root->tmbURL);
			$root->quarantine = Yii::getAlias($root->quarantine);
		}
		$roots = ArrayHelper::toArray($this->roots);

		$this->_elfinder = new ElFinderApi([
			'roots' => $roots
		]);

		$afterEvents  = [
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
		$beforeEvents = array_map(function($item) {
			return $item.'.pre';
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
	 */
	public function handleApiBeforeEvent($cmd, &$args, $instance, $dstVolume) {
		$event = Yii::createObject([
			'class'  => 'yii\base\Event',
			'name'   => $cmd,
			'sender' => $instance,
			'data'   => [
				'arguments' => &$args,
				'volume'    => $dstVolume
			]
		]);
		/* @var $event \yii\base\Event */

		$this->trigger($cmd, $event);

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
	 */
	public function handleApiAfterEvent($cmd, &$result, $args, $instance, $dstVolume) {
		$event = Yii::createObject([
			'class'  => 'yii\base\Event',
			'name'   => $cmd,
			'sender' => $instance,
			'data'   => [
				'arguments' => $args,
				'volume'    => $dstVolume,
				'result'    => &$result
			]
		]);
		/* @var $event \yii\base\Event */

		$this->trigger($cmd, $event);

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
	 */
	public function handleApiUploadBeforeSave(&$path, &$name, $tmpname, $instance, $dstVolume) {
		$event = Yii::createObject([
			'class'  => 'yii\base\Event',
			'name'   => self::EVENT_UPLOAD_BEFORE_SAVE,
			'sender' => $instance,
			'data'   => [
				'path'    => &$path,
				'name'    => &$name,
				'tmpname' => $tmpname,
				'volume'  => $dstVolume
			]
		]);
		/* @var $event \yii\base\Event */

		$this->trigger(self::EVENT_UPLOAD_BEFORE_SAVE, $event);

		return $event->handled;
	}
}