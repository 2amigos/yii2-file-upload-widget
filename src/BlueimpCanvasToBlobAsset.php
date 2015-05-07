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
class BlueimpCanvasToBlobAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-canvas-to-blob';
    public $js = [
        'js/canvas-to-blob.min.js',
    ];
}
