<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace dosamigos\fileupload\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;

/**
 * FileListAction it handles listing of your document.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @package dosamigos\fileupload\actions
 */
class FileListAction extends Action
{
    /**
     * @var string the full qualified namespace name of the model's search class -ie `\common\models\PictureSearch`
     */
    public $searchClass;
    /**
     * @var string the owner relation name of the model's class -ie `Picture::cities`, cities is the relation name of
     * `Picture` class to links to its owner, on this case `City` model.
     */
    public $ownerRelation;
    /**
     * @var string the owner table name -ie `city`
     */
    public $ownerTable;
    /**
     * @var string the name of the view where the resulting `$dataProvider` attribute will be pass through.
     */
    public $view;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->ownerRelation, $this->ownerTable, $this->searchClass, $this->view)) {
            throw new InvalidConfigException(
                '"searchClass", "ownerRelation", "ownerTable" and "view" attributes cannot be null'
            );
        }
        parent::init();
    }

    /**
     * Builds the data provider that needs to be rendered. If you wish to modify it, you will have to do it on the
     * view itself -ie change pager settings etc.
     *
     * @inheritdoc
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $ownerId = Yii::$app->request->get('ownerId');
        if (!$ownerId) {
            throw new NotFoundHttpException();
        }
        $searchClass = new $this->searchClass();
        $searchClass->innerJoinWith = [
            $this->ownerRelation => function ($q) use ($ownerId) {
                $q->andWhere([$this->ownerTable . ".id" => $ownerId]);
            }
        ];
        $method = Yii::$app->request->isPjax ? 'renderAjax' : 'render';

        $this->controller->{$method}($this->view, ['ownerId' => $ownerId, 'dataProvider' => $searchClass->search([])]);

        throw new NotFoundHttpException(Yii::t('fileupload', 'Page not found'));
    }
}
