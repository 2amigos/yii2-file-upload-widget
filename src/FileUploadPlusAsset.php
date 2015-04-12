<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadPlusAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class FileUploadPlusAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
        'blueimp-file-upload/css/jquery.fileupload.css'
    ];
    public $js = [
        'blueimp-load-image/js/load-image.min.js',
        'blueimp-canvas-to-blob/js/canvas-to-blob.min.js',
        'blueimp-file-upload/js/jquery.iframe-transport.js',
        'blueimp-file-upload/js/jquery.fileupload-process.js',
        'blueimp-file-upload/js/jquery.fileupload-image.js',
        'blueimp-file-upload/js/jquery.fileupload-audio.js',
        'blueimp-file-upload/js/jquery.fileupload-video.js',
        'blueimp-file-upload/js/jquery.fileupload-validate.js'
    ];
    public $depends = [
        'dosamigos\fileupload\FileUploadAsset',
    ];
}
