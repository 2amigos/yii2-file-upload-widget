<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
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
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\fileupload
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
        echo $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);

        $this->registerClientScript();
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        $bundle = FileUploadAsset::register($view);
        if($this->plus) {
            $bundle->js[] = 'blueimp-load-image/js/load-image.min.js';
            $bundle->js[] = 'blueimp-canvas-to-blob/js/canvas-to-blob.min.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.iframe-transport.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.fileupload-process.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.fileupload-image.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.fileupload-audio.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.fileupload-video.js';
            $bundle->js[] = 'blueimp-file-upload/js/jquery.fileupload-validate.js';
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
