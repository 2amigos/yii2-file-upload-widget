# BlueImp File Upload Widget for Yii2

[![Latest Version](https://img.shields.io/github/tag/2amigos/yii2-file-upload-widget.svg?style=flat-square&label=release)](https://github.com/2amigos/yii2-file-upload-widget/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/2amigos/yii2-file-upload-widget/master.svg?style=flat-square)](https://travis-ci.org/2amigos/yii2-file-upload-widget)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/2amigos/yii2-file-upload-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-file-upload-widget/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/2amigos/yii2-file-upload-widget.svg?style=flat-square)](https://scrutinizer-ci.com/g/2amigos/yii2-file-upload-widget)
[![Total Downloads](https://img.shields.io/packagist/dt/2amigos/yii2-file-upload-widget.svg?style=flat-square)](https://packagist.org/packages/2amigos/yii2-file-upload-widget)

Renders a [BlueImp jQuery File Upload plugin](http://blueimp.github.io/jQuery-File-Upload/).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require 2amigos/yii2-file-upload-widget:~1.0
```

or add

```
"2amigos/yii2-file-upload-widget": "~1.0"
```

to the `require` section of your `composer.json` file.

## Usage

The widget comes with two flavors:

- FileUpload: [Basic](http://blueimp.github.io/jQuery-File-Upload/basic.html) and [BasicPlus](http://blueimp.github.io/jQuery-File-Upload/basic-plus.html)
- FileUploadUI: [BasicPlus UI](http://blueimp.github.io/jQuery-File-Upload/index.html)

```PHP
<?php
use dosamigos\fileupload\FileUpload;

// without UI
?>

<?= FileUpload::widget([
	'model' => $model,
	'attribute' => 'image',
	'url' => ['media/upload', 'id' => $model->id], // your url, this is just for demo purposes,
	'options' => ['accept' => 'image/*'],
	'clientOptions' => [
		'maxFileSize' => 2000000
	],
	// Also, you can specify jQuery-File-Upload events
	// see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
	'clientEvents' => [
	    'fileuploaddone' => 'function(e, data) {
	                            console.log(e);
	                            console.log(data);
	                        }',
        'fileuploadfail' => 'function(e, data) {
	                            console.log(e);
	                            console.log(data);
                            }',
	],
]);?>

<?php

// with UI

use dosamigos\fileupload\FileUploadUI;
?>
<?= FileUploadUI::widget([
	'model' => $model,
	'attribute' => 'image',
	'url' => ['media/upload', 'id' => $tour_id],
	'gallery' => false,
	'fieldOptions' => [
    		'accept' => 'image/*'
	],
	'clientOptions' => [
    		'maxFileSize' => 2000000
	],
	// ...
	'clientEvents' => [
    	    'fileuploaddone' => 'function(e, data) {
    	                            console.log(e);
    	                            console.log(data);
    	                        }',
            'fileuploadfail' => 'function(e, data) {
    	                            console.log(e);
    	                            console.log(data);
                                }',
    ],
]);
?>
```

Please, check the [jQuery File Upload documentation](https://github.com/blueimp/jQuery-File-Upload/wiki) for further information about its configuration options.

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Antonio Ramirez](https://github.com/tonydspaniard)
- [Alexander Kochetov](https://github.com/creocoder)
- [All Contributors](https://github.com/2amigos/yii2-selectize-widget/graphs/contributors)

## License

The BSD License (BSD). Please see [License File](LICENSE.md) for more information.

<blockquote>
    <a href="http://www.2amigos.us"><img src="http://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png"></a><br>
    <i>web development has never been so fun</i><br>
    <a href="http://www.2amigos.us">www.2amigos.us</a>
</blockquote>
