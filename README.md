# yii2-elfinder
This extension integrates the jQuery elFinder into yii2 framework (the yii way: with elFinder component, behaviors, 
configurable objects, events etc.). Additionally it prevents jui/elfinder css from loading and provides it's own styles
for elfinder (bootstrap3 / fontawesome scss based style).

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
		'class'          => 'simialbi\yii2\elfinder\Module',
		'options'        => [
			'default' => [
				'locale'     => 'de_DE.UTF-8',
				'maxTargets' => 0
			]
		],
		'connectionSets' => [
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
					'class'	    => 'simialbi\yii2\elfinder\behaviors\ImageResizeBehavior',
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

## Example Usage

### Elfinder widget

To include an elfinder instance in one of your pages, call the elfinder widget like this:
```php
<?php
/* @var $this yii\web\View */

use simialbi\yii2\elfinder\widgets\ElFinder;

$this->title = 'elFinder';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="my-elfinder">
<?php
	// @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options-2.1
	echo ElFinder::widget([
		'instanceName' => 'default' // from module connectionSets/volumeBehaviors configuration (array key)
	]);
?>
</div>
```

All options from [elfinder client configuration options](https://github.com/Studio-42/elFinder/wiki/Client-configuration-options-2.1)
and `instanceName` can be used to configure the widget.

### ElFinderInput widget

To include an elfinder input field widget, call the input widget like this:
```php
<?php
/* @var $this yii\web\View */

use simialbi\yii2\elfinder\widgets\ElFinderInput;

$this->title = 'elFinder';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="my-elfinder">
<?php
	// @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options-2.1
	echo ElFinderInput::widget([
		'name' => 'my-file',
		'value' => '/path/to/my/file.ext',
		'instanceName' => 'default'
	]);
	// or model like usage
	/* @var $form \yii\widgets\ActiveForm */
	/* @var $model \yii\base\Model */
	echo $form->field($model, 'my-file')->widget(
		ElFinderInput::className(),
		[
		   'instanceName' => 'default'
		]
	);
?>
</div>
```

## License

**yii2-elfinder** is released under MIT license. See bundled [LICENSE](LICENSE) for details.