<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace dosamigos\fileupload\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * FileDeleteAction handles file deletion
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 */
class FileDeleteAction extends Action
{
    /**
     * @var string the AR class name that we need to delete
     */
    public $className;
    /**
     * @var string the parameter name in the request. Defaults to 'id'.
     */
    public $idParamName = 'id';
    /**
     * @var string the name of the table handling relations -ie city_picture_assn
     */
    public $ownerLinkTable;
    /**
     * @var string the name of the attribute of the class we are going to delete -ie picture_id
     */
    public $ownerLinkTableAttribute;
    /**
     * @var string|array the route to redirect after successful deletion. Note: If the user is doing and AJAX'ed
     * request. Will simply return a JSON with `success` true or false. If `success` it will contain a `message`,
     * otherwise will contain also `errors` key so you can display the issues on a notification plugin or whatever.
     */
    public $redirectRoute = 'index';

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->ownerLinkTable, $this->className)) {
            throw new InvalidConfigException('"ownerLinkTable" and "className" attributes cannot be null');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     *
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $id = Yii::$app->request->get($this->idParamName);
        if (!$id) {
            throw new NotFoundHttpException(Yii::t('fileupload', 'Page not found'));
        }
        $model = call_user_func([$this->className, 'findOne'], $id);
        $response = ['success' => $model->delete()];

        if (Yii::$app->request->isPost) {

            $shortClassName = (strpos($this->className, '\\') === false
                ? $this->className
                : substr($this->className, strrpos($this->className, '\\') + 1));

            if (Yii::$app->request->isAjax) { // handling AJAX'ed requests

                Yii::$app->response->format = Response::FORMAT_JSON;
                $response['success'] = $model->delete();
                if ($response['success']) {
                    $this->unlink($id);
                    $response['message'] = $shortClassName . ' ' . Yii::t('fileupload', 'successfully removed.');
                } else {
                    $response['message'] = Yii::t('fileupload', 'Unable to remove') . $shortClassName;
                    $response['errors'] = implode("\n", $this->getModelErrors($model));
                }

                return $response;

            } else { // handling not AJAX'ed requests

                if ($model->delete()) {
                    Yii::$app->session->addFlash(
                        'success',
                        $shortClassName . ' ' . Yii::t('fileupload', 'successfully removed.')
                    );
                } else {
                    Yii::$app->session->addFlash('error', Yii::t('fileupload', 'Unable to remove') . $shortClassName);
                }
            }
        }

        return $this->controller->redirect($this->redirectRoute);

    }

    /**
     * Removed
     *
     * @param mixed $id the id of the class to remove from link table
     */
    protected function unlink($id)
    {
        ActiveRecord::getDb()
            ->createCommand()
            ->delete($this->ownerLinkTable, [$this->ownerLinkTableAttribute => $id])
            ->execute();
    }

    /**
     * Helper function
     *
     * @param \yii\base\Model $model
     *
     * @return array
     * @todo breaks DRY. Think of the best way to implement.
     */
    protected function getModelErrors($model)
    {
        $errors = [];
        foreach ($model->getFirstErrors() as $error) {
            $errors[] = $error;
        }

        return $errors;
    }

}
