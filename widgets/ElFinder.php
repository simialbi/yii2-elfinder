<?php
namespace simialbi\yii2\elfinder\widgets;


use simialbi\yii2\elfinder\ElFinderAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use Yii;

/**
 * ElFinder widget renders elfinder instance.
 *
 * ```php
 * <?php echo ElFinder::widget([
 *      'instanceName' => 'default' // from module connectionSets/volumeBehaviors configuration (array key)
 * ]); ?>
 * ```
 *
 * @author Simon Karlen <simi.albi@gmail.com>
 * @since 1.0
 */
class ElFinder extends Widget {
	const VIEW_ICONS = 'icons';
	const VIEW_LIST = 'list';

	const SORT_NAME = 'name';
	const SORT_KIND = 'kind';
	const SORT_SIZE = 'size';
	const SORT_DATE = 'date';

	const FILE_MODE_STRING = 'string';
	const FILE_MODE_OCTAL = 'octal';
	const FILE_MODE_BOTH = 'both';

	const REQUEST_GET = 'get';
	const REQUEST_POST = 'post';

	/**
	 * @var string/array Connector URL. This is the only required option. Can be absolute or relative
	 */
	public $url = '';

	/**
	 * @var string The interface lang to use. Can currently be one of the following: ar, bg, ca, cs, de, en, es, fr, hu,
	 * jp, nl, pl, pt_BR, ru, zh_CN. You will also need to include corresponding language file from js/i18n directory.
	 */
	public $lang;

	/**
	 * @var array Data to append to all requests and to upload files. These can be any extra data that must be passed to
	 * the connector script. For example, you could use these to pass authentication information.
	 */
	public $customData;

	/**
	 * @var string Additional css class for filemanager node. This will be applied to the main filemanager container.
	 */
	public $cssClass;

	/**
	 * @var boolean|array Auto load required CSS (elfinder.min.css and theme.css).
	 *
	 * false to disable this function or CSS URL Array to load additional CSS files
	 */
	public $cssAutoLoad = false;

	/**
	 * @var boolean Remeber last opened dir to open it after reload or in next session. This is stored in browser cookie.
	 */
	public $rememberLastDir;

	/**
	 * @var boolean Clear historys(elFinder) on reload(not browser) function. Historys was cleared on Reload function on
	 * elFinder 2.0. (value is true)
	 */
	public $reloadClearHistory;

	/**
	 * @var boolean Use browser native history by hash-change with supported browsers. This option give URI hash that
	 * holder position hash of elFinder.
	 */
	public $useBrowserHistory;

	/**
	 * @var array Display only certain files based on their mime type.
	 */
	public $onlyMimes;

	/**
	 * @var boolean|string|\yii\web\JsExpression Used to validate file names. By default, no empty names or '..' allowed.
	 */
	public $validName;

	/**
	 * @var string Hash of default directory path to open.
	 *
	 * NOTE: This setting will be disabled if the target folder is specified in location.hash.
	 *
	 * If you want to find the hash in Javascript can be obtained with the following code.
	 * (In the case of a standard hashing method)
	 *
	 * ```javascript
	 * var volumeId = 'l1_'; // volume id
	 * var path = 'path/to/target'; // without root path
	 * //var path = 'path\\to\\target'; // use \ on windows server
	 * var hash = volumeId + btoa(path).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '.').rep
	 * ```
	 */
	public $startPathHash;

	/**
	 * @var string Default view mode. Can be [[ElFinder::VIEW_ICONS]] and [[ElFinder::VIEW_LIST]].
	 */
	public $defaultView = self::VIEW_ICONS;

	/**
	 * @var string Default sort type. Can be [[ElFinder::SORT_NAME]], [[ElFinder::SORT_KIND]], [[ElFinder::SORT_SIZE]]
	 * or [[ElFinder::SORT_DATE]]
	 */
	public $sortType;

	/**
	 * @var integer Default sort order. Either [[SORT_ASC]] or [[SORT_DESC]]
	 */
	public $sortOrder = SORT_ASC;

	/**
	 * @var boolean Display folders first?
	 */
	public $sortStickFolders = true;

	/**
	 * @var string|integer The width of the elFinder main interface.
	 */
	public $width;

	/**
	 * @var integer The height of the elFinder main interface (in pixels).
	 */
	public $height;

	/**
	 * @var boolean Format dates using client. If set to false - backend date format will be used.
	 */
	public $clientFormatDate;

	/**
	 * @var boolean Show datetime in UTC timezone. Requires clientFormatDate set to true.
	 */
	public $UTCDate;

	/**
	 * @var string File modification datetime format. Value from selected language is used by default.
	 * Set format here to overwrite it. Format is set in PHP date maner
	 */
	public $dateFormat;

	/**
	 * @var string File modification datetime format for last two days (today, yesterday).
	 * Same syntax as for dateFormat. Use $1 for "Today" and "Yesterday" placeholder.
	 */
	public $fancyDateFormat;

	/**
	 * @var string Style of file mode at cwd-list, info dialog [[ElFinder::FILE_MODE_STRING]] (ex. rwxr-xr-x)
	 * or [[ElFinder::FILE_MODE_OCTAL]] (ex. 755) or [[ElFinder::FILE_MODE_BOTH]] (ex. rwxr-xr-x (755))
	 */
	public $fileModeStyle;

	/**
	 * @var array Active commands list. You can set any list of enabled commands here if some minimal required commands
	 * will be missed here, elFinder will add them to list automatically.
	 *
	 * '*' means all of the commands that have been load.
	 */
	public $commands;

	/**
	 * @var null|\yii\web\JsExpression Commands options used to interact with external callbacks, editors, plugins.
	 */
	public $commandsOptions;

	/**
	 * @var null|\yii\web\JsExpression Callback function for "getfile" command. Required to use elFinder with WYSIWYG
	 * editors, external callbacks.
	 * For more info how to use this function refer to wiki WYSIWYG integrations examples.
	 */
	public $getFileCallback;

	/**
	 * @var \yii\web\JsExpression[] Event listeners to bind on elFinder init.
	 * For more info refer Client event API.
	 */
	public $handlers;

	/**
	 * @var array UI plugins to load. places value not activated by default.
	 *
	 * Full value:
	 * ```javascript
	 * ['toolbar', 'places', 'tree', 'path', 'stat']
	 * ```
	 */
	public $ui;

	/**
	 * @var null|array Specifies the configuration for the elFinder UI.
	 */
	public $uiOptions;

	/**
	 * @var array The configuration for the right-click context menu
	 */
	public $contextmenu;

	/**
	 * @var boolean Whether or not the elFinder interface will be resizable. This only works if jQuery UI has the
	 * resizable plugin loaded.
	 */
	public $resizable;

	/**
	 * @var integer Timeout in ms for open notification dialogs.
	 */
	public $notifyDelay;

	/**
	 * @var array Position and width of notification dialogs.
	 */
	public $notifyDialog;

	/**
	 * @var string|boolean Allow to drag and drop to upload files.
	 */
	public $dragUploadAllow;

	/**
	 * @var boolean Allow shortcuts
	 */
	public $allowShortcuts;

	/**
	 * @var integer Number of thumbnails to create per one request
	 */
	public $loadTmbs;

	/**
	 * @var integer Lazy load. Amount of files display at once.
	 */
	public $showFiles;

	/**
	 * @var integer Lazy load. Distance in px to cwd bottom edge to start displaying files.
	 */
	public $showThreshold;

	/**
	 * @var string The AJAX request type. Available choices are [[ElFinder::REQUEST_POST]]
	 * and [[ElFinder::REQUEST_GET]].
	 */
	public $requestType;

	/**
	 * @var string Separate URL to upload file to. If not set - connector URL will be used.
	 */
	public $urlUpload;

	/**
	 * @var integer Timeout for upload using iframe.
	 */
	public $iframeTimeout;

	/**
	 * @var integer Sync content by refreshing cwd every N milliseconds. Value must be bigger than 1000. 0 = no sync
	 */
	public $sync;

	/**
	 * @var array Cookie option for browsers that does not support localStorage
	 */
	public $cookie;

	/**
	 * @var array Passing custom headers during Ajax calls
	 */
	public $customHeaders;

	/**
	 * @var array Any custom xhrFields to send across every ajax request, useful for CORS
	 * (Cross-origin resource sharing) support
	 */
	public $xhrFields;

	/**
	 * @var array|boolean Debug config
	 */
	public $debug;

	/**
	 * @var integer Increase the size of individual chunks.
	 */
	public $uploadMaxChunkSize;

	/**
	 * @var array the HTML attributes for the title tag.
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $options = [];

	/**
	 * @var string name of the instance used in module configuration (defaults to default)
	 */
	public $instanceName = 'default';

	/**
	 * @inheritdoc
	 */
	public function init() {
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		if (empty($this->url)) {
			$this->url = ['elfinder/connection', 'instanceName' => $this->instanceName];
		} else {
			if (!is_array($this->url)) {
				$this->url = [$this->url];
			}
			$this->url['instanceName'] = $this->instanceName;
		}
		if (empty($this->lang)) {
			$this->lang = Yii::$app->language;
		}

		$this->url = Url::to($this->url);

		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run() {
		echo Html::tag('div', null, $this->options);

		$this->registerPlugin();
	}

	/**
	 * Registers the elfinder javascript assets and builds the required js for the widget
	 */
	protected function registerPlugin() {
		$id   = $this->options['id'];
		$view = $this->getView();

		ElFinderAsset::register($view);

		$js = [
			"jQuery('#$id').data('cssautoloadHide', jQuery('<style>.elfinder{visibility:hidden;overflow:hidden}</style>'));",
			"jQuery('#$id').elfinder({$this->getClientOptions()});"
		];

		$view->registerJs(implode("\n", $js), View::POS_READY);
	}

	/**
	 * Get client options as json encoded string
	 *
	 * @return string
	 */
	protected function getClientOptions() {
		$options = get_object_vars($this);
		if ($options['sortOrder'] === SORT_DESC) {
			$options['sortOrder'] = 'desc';
		} else {
			$options['sortOrder'] = 'asc';
		}

		unset($options['options']);

		foreach ($options as $key => $option) {
			if (is_null($option) || substr($key, 0, 1) === '_') {
				unset($options[$key]);
			}
		}

		return Json::encode($options);
	}
}