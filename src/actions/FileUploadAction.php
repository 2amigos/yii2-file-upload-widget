<?php
/**
 * @link https://github.com/2amigos/yii2-file-upload-widget
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
namespace dosamigos\fileupload\actions;

use Exception;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * FileUploadAction handles the file upload. This class is the base class to create your very own Upload action.
 * OVERRIDE its "upload" function to implement your own.
 *
 * @author Antonio Ramirez <hola@2amigos.us>
 * @package dosamigos\fileupload\actions
 */
class AbstractUploadAction extends Action
{
    /**
     * @var string the AR class name that we need to link the uploaded instance to
     */
    public $className;
    /**
     * @var string the attribute name the class is using to upload files
     */
    public $uploadAttributeName;
    /**
     * @var string the attribute name of the class that validates the uploaded files
     */
    public $fileAttributeName;
    /**
     * @var string the attribute name of the class that will contain the web url reference -ie url
     */
    public $urlAttributeName = 'url';
    /**
     * @var string the attribute name of the class that will contain thumbnail's web url reference -ie thumbnail.
     * Could also be `url` but then make sure the resize is correct.
     */
    public $thumbnailUrlAttributeName = 'thumbnail';
    /**
     * @var string the owner table link -ie city_picture_assn
     */
    public $ownerLinkTable;
    /**
     * @var string the owner's attribute in the table link -ie city_id
     */
    public $ownerLinkTableOwnerAttribute;
    /**
     * @var string the name of the attribute of the class we are going to upload and link -ie picture_id
     */
    public $ownerLinkTableAttribute;
    /**
     * @var string the parameter name where we can get the owner id. Defaults to 'ownerId'
     */
    public $ownerIdParamName = 'ownerId';
    /**
     * @var array the parameter name so to add the delete
     */
    public $deleteUrlRoute = ['delete'];
    /**
     * @var string the parameter name to pass to the delete Url route
     */
    public $deleteIdParamName = 'id';
    /**
     * @var string the web url where they are stored. -ie http://myexample.com/files
     */
    public $filesWebUrl;
    /**
     * @var string the path alias where to upload the files. -ie @frontend/web/files
     */
    public $filePathAlias;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->className, $this->ownerLinkTableOwnerAttribute, $this->ownerLinkTable, $this->ownerLinkTableAttribute)) {
            throw new InvalidConfigException(
                '"className", "ownerLinkTableOwnerAttribute", "ownerLinkAttribute" and "ownerLinkTable" cannot be null'
            );
        }
        parent::init();
    }

    /**
     * @inheritdoc
     *
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $ownerId = Yii::$app->request->get($this->ownerIdParamName);
        if (!$ownerId) {
            throw new NotFoundHttpException(Yii::t('fileupload', 'Page not found'));
        }
        /** @var ActiveRecord $model */
        $model = new $this->className();
        $model->{$this->fileAttributeName} = UploadedFile::getInstance($model, $this->uploadAttributeName);

        if (isset($model->{$this->fileAttributeName}) && $model->validate($this->{$this->fileAttributeName})) {

            Yii::$app->response->getHeaders()->set('Vary', 'Accept');
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response = [];
            $filePath = null;
            $transaction = ActiveRecord::getDb()->beginTransaction();

            try {
                $this->upload($model);
                $deleteRoute = $this->deleteUrlRoute + [$this->deleteIdParamName => $model->id];
                $response['files'][] = [
                    'name' => $model->{$this->fileAttributeName}->name,
                    'type' => $model->{$this->fileAttributeName}->type,
                    'size' => $model->{$this->fileAttributeName}->size,
                    'url' => $model->{$this->urlAttributeName},
                    'thumbnailUrl' => $model->{$this->thumbnailUrlAttributeName},
                    'deleteUrl' => Url::to($deleteRoute),
                    'deleteType' => 'POST'
                ];

                $this->link($ownerId, $model->id);

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                $response[] = [
                    'error' => Yii::t(
                        'fileupload',
                        'Unable to save picture: `{errorMessage}`',
                        ['errorMessage' => $e->getMessage()]
                    ),
                ];

                @unlink($model->{$this->fileAttributeName}->tempName);
            }
        } else {
            if ($model->hasErrors([$this->fileAttributeName])) {
                $response[] = ['errors' => $this->getModelErrors($model)];
            } else {
                throw new HttpException(500, Yii::t('fileupload', 'Could not upload file.'));
            }
        }

        return $response;
    }

    /**
     * Links uploaded file to its owner.
     *
     * @param mixed $ownerId
     * @param mixed $id
     */
    protected function link($ownerId, $id)
    {
        ActiveRecord::getDb()
            ->createCommand()
            ->insert(
                $this->ownerLinkTable,
                [$this->ownerLinkTableAttribute => $ownerId, $this->ownerLinkTableAttribute => $id]
            )
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

    /**
     * Handles files upload Override as needed.
     *
     * @param ActiveRecord $model
     *
     * @return mixed
     *
     * @throws Exception
     */
    protected function upload($model)
    {
        /** @var UploadedFile $file */
        $file = $model->{$this->fileAttributeName};
        $path = Yii::getAlias($this->filePathAlias);
        $filename = md5(mt_rand()) . "-{$file->baseName}.{$file->extension}";

        if (!$file->saveAs($path . '/' . $filename)) {
            throw new Exception(Yii::t('fileupload', 'Cannot save uploaded file'));
        }
        $model->{$this->urlAttributeName} = $this->filesWebUrl . '/' . $filename;
        // thumbnail url is the same as the url. Extend from this class and override this method
        // to implement your thumbnail logic.
        $model->{$this->thumbnailUrlAttributeName} = $model->{$this->urlAttributeName};

        if (!$model->save(false)) {
            @unlink($path . '/' . $filename);
            throw new Exception(Yii::t('fileupload', 'Cannot save file model.'));
        }
    }
}
