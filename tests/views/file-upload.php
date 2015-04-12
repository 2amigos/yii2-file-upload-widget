<?php

use dosamigos\fileupload\FileUpload;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $model tests\models\Model */
?>

<?= FileUpload::widget([
    'url' => 'http://example.com/upload.php',
    'model' => $model,
    'attribute' => 'test',
]) ?>

<?= FileUpload::widget([
    'url' => 'http://example.com/upload.php',
    'name' => 'test',
]) ?>

<?= FileUpload::widget([
    'url' => 'http://example.com/upload.php',
    'id' => 'custom-id',
    'name' => 'test',
    'plus' => true,
    'clientEvents' => [
        'test2' => new JsExpression('function () { }'),
    ],
]) ?>
