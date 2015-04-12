<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace tests;

use dosamigos\fileupload\FileUpload;
use tests\models\Model;
use Yii;

/**
 * FileUploadTest
 */
class FileUploadTest extends \PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $model = new Model();
        $view = Yii::$app->getView();
        $content = $view->render('//file-upload', ['model' => $model]);
        $actual = $view->render('//layouts/main', ['content' => $content]);
        $expected = file_get_contents(__DIR__ . '/data/test-file-upload.bin');
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testWidgetExceptionIsRaisedWhenUrlIsEmpty()
    {
        FileUpload::begin(['name' => 'test']);
    }
}
