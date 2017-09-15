# yii2-elfinder
This extension integrates the jQuery elFinder into yii2 framework (the yii way: with elFinder component, behaviors, 
configurable objects etc.). Additionally it prevents jui css from loading and provides it's own styles for elfinder 
(bootstrap 3 scss style).

## Resources
 * [elFinder](https://github.com/Studio-42/elFinder)
 * [yii2](https://github.com/yiisoft/yii2) framework
 
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require --prefer-dist simialbi/yii2-elfinder
```

or add 

```
"simialbi/yii2-elfinder": "*"
```

to the ```require``` section of your `composer.json`

## Usage

### Setup Module

Add the module `elfinder` to the modules section of your configuration file:

```php
'modules' => [
	'elfinder' => [
		'class'           => 'simialbi\yii2\elfinder\Module',
		'connectionSets'  => [
			'default' => [ // like elfinder roots
				[
					'class' => 'simialbi\yii2\elfinder\ElFinderConfigurationLocalFileSystem',
					'path'  => '@webroot/files',
					'URL'   => '@web/files'
				]
			]
		],
		'volumeBehaviors' => [
			'default' => [ // like elfinder plugins, add behaviors
				'as resizer'   => [
					'class'     => 'simialbi\yii2\elfinder\behaviors\ImageResizeBehavior',
					'maxWidth'  => 1920,
					'maxHeight' => 1080,
					'quality'   => 70
				],
				'as optimizer' => [
					'class' => 'simialbi\yii2\elfinder\behaviors\ImageOptimizeBehavior'
				]
			]
		]
	]
]
```

### Configuration

`TODO`

## Example Usage

To include an elfinder instance in one of your pages, call the widget like this:
```php
<?php
/* @var $this yii\web\View */

use simialbi\yii2\elfinder\widgets\ElFinder;

$this->title = 'elFinder';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="my-elfinder">
<?php
	echo ElFinder::widget([
		'instanceName' => 'default' // from module connectionSets/volumeBehaviors configuration (array key)
	]);
?>
</div>

```

## License

**yii2-elfinder** is released under MIT license. See bundled [LICENSE](LICENSE) for details.