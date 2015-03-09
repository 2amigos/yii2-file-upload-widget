<?php
/**
 * @copyright Copyright (c) 2013 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\fileupload;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * BaseUpload
 *
 * Base class for both uploaders.
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\fileupload
 */
class BaseUpload extends InputWidget
{
    /**
     * @var string|array upload route
     */
    public $url;
    /**
     * @var array the plugin options. For more information see the jQuery File Upload options documentation.
     * @see https://github.com/blueimp/jQuery-File-Upload/wiki/Options
     */
    public $clientOptions = [];
    /**
     * @var array the event handlers for the jQuery File Upload plugin.
     * Please refer to the jQuery File Upload plugin web page for possible options.
     * @see https://github.com/blueimp/jQuery-File-Upload/wiki/Options#callback-options
     */
    public $clientEvents = [];


    public $i18n = [];
    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->initI18N();

        if(empty($this->url)) {
            throw new InvalidConfigException('"url" cannot be empty.');
        }

        $this->clientOptions['url'] = Url::to($this->url);
    }
    
    public function initI18N()
    {
        Yii::setAlias('@fileupload', dirname(__FILE__));
        if (empty($this->i18n)) {
            $this->i18n = [
                'sourceLanguage' => 'en',
                'basePath' => '@fileupload/messages',
                'class' => 'yii\i18n\PhpMessageSource',
            ];
        }
        Yii::$app->i18n->translations['fileupload'] = $this->i18n;
    }
} 