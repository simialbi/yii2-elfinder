<?php

namespace simialbi\yii2\elfinder\widgets;

use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use Yii;

/**
 * ElFinderInput widget renders an bootstrap form group with input group button which opens an elfinder modal for
 * selecting files.
 *
 * ```php
 * <?php echo ElFinderInput::widget([
 *      'name' => 'my-file',
 *      'value' => '/path/to/my/file.ext',
 *      'instanceName' => 'default'
 * ]); ?>
 * ```
 * or with model usage
 * ```php
 * <?php echo $form->field($model, 'my-file')->widget(
 *      ElFinderInput::className(),
 *      [
 *          'instanceName' => 'default'
 *      ]
 * ); ?>
 * ```
 *
 * @author Simon Karlen <simi.albi@gmail.com>
 * @since 1.1
 */
class ElFinderInput extends InputWidget {
	/**
	 * @var array the HTML attributes (name-value pairs) for the field container tag.
	 * The values will be HTML-encoded using [[Html::encode()]].
	 * If a value is `null`, the corresponding attribute will not be rendered.
	 * The following special options are recognized:
	 *
	 * - `tag`: the tag name of the container element. Defaults to `div`. Setting it to `false` will not render a container tag.
	 *   See also [[\yii\helpers\Html::tag()]].
	 *
	 * If you set a custom `id` for the container element, you may need to adjust the [[$selectors]] accordingly.
	 *
	 * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
	 */
	public $options = ['class' => 'form-control'];

	/**
	 * @var string label text. It will NOT be HTML-encoded. Therefore you can pass in HTML code
	 * such as an image tag. If this is is coming from end users, you should [[encode()]]
	 * it to prevent XSS attacks.
	 */
	public $label;

	/**
	 * @var string
	 */
	public $icon = '<span class="glyphicon glyphicon-option-horizontal"></span>';

	/**
	 * @var string
	 */
	public $modalIcon = '<span class="glyphicon glyphicon-folder-open"></span>';

	/**
	 * @var string name of the instance used in module configuration (defaults to default)
	 */
	public $instanceName = 'default';

	/**
	 * @var array ElFinder widget configuration options
	 */
	public $elfinderOptions = [];

	/**
	 * @inheritdoc
	 */
	public function init() {
		if (!isset($this->options['id'])) {
			if ($this->hasModel()) {
				$this->options['id'] = Html::getInputId($this->model, $this->attribute);
			} else {
				$this->options['id'] = $this->getId();
			}
		}

		$this->registerTranslations();

		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run() {
		$options = $this->options;

		$label = Yii::t('simialbi/elfinder/input-widget', 'Choose file');
		if ($this->hasModel()) {
			$label = $this->model->getAttributeLabel($this->attribute);
		}
		$html = Html::beginTag('div', [
			'class' => 'input-group'
		]);
		if ($this->hasModel()) {
			$html .= Html::activeTextInput($this->model, $this->attribute, $options);
		} else {
			$html .= Html::textInput($this->name, $this->value, $options);
		}
		$html .= Html::tag('div', Html::button($this->icon, [
			'id'    => $options['id'].'-btn',
			'class' => ['btn', 'btn-default'],
			'data'  => [
				'toggle' => 'modal',
				'target' => '#'.$options['id'].'-modal'
			]
		]), [
			'class' => 'input-group-btn'
		]);
		$html .= Html::endTag('div');

		ob_start();
		Modal::begin([
			'id'     => $options['id'].'-modal',
			'size'   => Modal::SIZE_LARGE,
			'header' => $this->modalIcon.Html::tag('h4', $label, ['class' => 'modal-title']),
			'footer' => Html::button(Yii::t('simialbi/elfinder/input-widget', 'Close'), [
					'class'        => ['btn', 'btn-default'],
					'data-dismiss' => 'modal'
				]).Html::button(Yii::t('simialbi/elfinder/input-widget', 'Save'), [
					'class'        => ['btn', 'btn-primary', 'pull-right'],
					'data-dismiss' => 'modal'
				])
		]);
		$elfinderOptions                    = $this->elfinderOptions;
		$elfinderOptions['instanceName']    = $this->instanceName;
		$elfinderOptions['getFileCallback'] = new JsExpression("function (file) {
			var parser = document.createElement('a');
			parser.href = file.url;
			jQuery('#{$options['id']}').val(parser.pathname ? parser.pathname : file.url);
		}");
		echo ElFinder::widget($elfinderOptions);
		Modal::end();

		$html .= ob_get_clean();

		return $html;
	}

	/**
	 * Init translations
	 */
	public function registerTranslations() {
		Yii::$app->i18n->translations['simialbi/elfinder*'] = [
			'class'          => 'yii\i18n\GettextMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath'       => __DIR__.'/messages'
		];
	}
}