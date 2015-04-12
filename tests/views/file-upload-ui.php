<?php

use dosamigos\fileupload\FileUploadUI;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $model tests\models\Model */
?>

<?= FileUploadUI::widget([
    'url' => 'http://example.com/upload.php',
    'model' => $model,
    'attribute' => 'test',
]) ?>

<?= FileUploadUI::widget([
    'url' => 'http://example.com/upload.php',
    'name' => 'test',
]) ?>

<?= FileUploadUI::widget([
    'url' => 'http://example.com/upload.php',
    'id' => 'custom-id',
    'name' => 'test',
    'clientEvents' => [
        'test2' => new JsExpression('function () { }'),
    ],
]) ?>
