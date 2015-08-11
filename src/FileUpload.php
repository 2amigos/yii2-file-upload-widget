<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\fileupload;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * FileUpload
 *
 * Widget to render the jQuery File Upload Basic Uploader
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class FileUpload extends BaseUpload
{
    /**
     * @var bool whether to register the js files for the basic +
     */
    public $plus = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $url = Url::to($this->url);
        $this->options['data-url'] = $url;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);

        echo $this->render('uploadButton', ['input' => $input]);

        $this->registerClientScript();
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        if($this->plus) {
            FileUploadPlusAsset::register($view);
        } else {
            FileUploadAsset::register($view);
        }

        $options = Json::encode($this->clientOptions);
        $id = $this->options['id'];

        $js[] = ";jQuery('#$id').fileupload($options);";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}
