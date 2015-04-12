<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\fileupload;

use dosamigos\gallery\GalleryAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * FileUploadUI
 *
 * Widget to render the jQuery File Upload UI plugin as shown in
 * [its demo](http://blueimp.github.io/jQuery-File-Upload/index.html)
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class FileUploadUI extends BaseUpload
{
    /**
     * @var bool whether to use the Bootstrap Gallery on the images or not
     */
    public $gallery = true;
    /**
     * @var array the HTML attributes for the file input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $fieldOptions = [];
    /**
     * @var string the ID of the upload template, given as parameter to the tmpl() method to set the uploadTemplate option.
     */
    public $uploadTemplateId;
    /**
     * @var string the ID of the download template, given as parameter to the tmpl() method to set the downloadTemplate option.
     */
    public $downloadTemplateId;
    /**
     * @var string the form view path to render the JQuery File Upload UI
     */
    public $formView = 'form';
    /**
     * @var string the upload view path to render the js upload template
     */
    public $uploadTemplateView = 'upload';
    /**
     * @var string the download view path to render the js download template
     */
    public $downloadTemplateView = 'download';
    /**
     * @var string the gallery
     */
    public $galleryTemplateView = 'gallery';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->fieldOptions['multiple'] = true;
        $this->fieldOptions['id'] = ArrayHelper::getValue($this->options, 'id');

        $this->options['id'] .= '-form';
        $this->options['enctype'] = 'multipart/form-data';
        $this->options['uploadTemplateId'] = $this->uploadTemplateId ? : '#template-upload';
        $this->options['downloadTemplateId'] = $this->downloadTemplateId ? : '#template-download';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->render($this->uploadTemplateView);
        echo $this->render($this->downloadTemplateView);
        echo $this->render($this->formView);

        if ($this->gallery) {
            echo $this->render($this->galleryTemplateView);
        }

        $this->registerClientScript();
    }

    /**
     * Registers required script for the plugin to work as jQuery File Uploader UI
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        if ($this->gallery) {
            GalleryAsset::register($view);
        }

        FileUploadUIAsset::register($view);

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
