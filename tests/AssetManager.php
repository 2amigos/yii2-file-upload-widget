<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace tests;

/**
 * AssetManager
 */
class AssetManager extends \yii\web\AssetManager
{
    private $_hashes = [];
    private $_counter = 0;

    /**
     * @inheritdoc
     */
    public function hash($path) {
        if (!isset($this->_hashes[$path])) {
            $this->_hashes[$path] = $this->_counter++;
        }

        return $this->_hashes[$path];
    }
}
