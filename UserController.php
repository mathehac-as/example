<?php

namespace app\modules\lk\controllers;

use Yii;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;

/**
 * Default controller for the `lk` module
 */
class UserController extends Controller
{
    /**
     * Renders the profile-edit view for the module
     * @return string
     */
    public function actionProfileEdit()
    {
        $model = Yii::$app->users->getOneObject(Yii::$app->user->id);
        $countries = Yii::$app->countries->getListAsArray();
        $cities = Yii::$app->cities->getListAsArray();
        $conditions = ['Мастер' => 'Мастер', 'Новичек' => 'Новичек'];//Yii::$app->conditions->getList();
        $model_upload = new UploadForm();

        return $this->render('profile-edit', [
            'model' => $model,
            'countries' => $countries,
            'cities' => $cities,
            'conditions' => $conditions,
            'model_upload' => $model_upload
        ]);
    }
    
    /**
     * Renders the save view for the module
     * @return string
     */
    public function actionSave()
    {
        $model = Yii::$app->users->getOneObject(Yii::$app->user->id);
        try 
        {
            if ($model->load(Yii::$app->request->post())) 
            {
                $model_upload = new UploadForm();
                $model_upload->imageFile = UploadedFile::getInstance($model_upload, 'imageFile');
                if($model_upload->imageFile)
                {
                    if (!$model_upload->upload()) 
                    {
                        throw new Exception('Профиль не обновлен');
                    }
                    $model->avatar = '/'.$model_upload->filePath;
                }
                if ($model->save())
                {
                    Yii::$app->getSession()->setFlash('success', "Профиль обновлен");
                    return $this->redirect(['/lk']);
                }
                else
                {
                    throw new Exception('Профиль не обновлен');
                }
            }
            else
            {
                throw new Exception('Профиль не обновлен - не выбраны параметры');
            }
        }
        catch (\Exception $e) 
        {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['/lk/user/profile-edit']);
        }
    }
    
    /**
     * Renders the del avatar for the module
     * @return string
     */
    public function actionDelAvatar()
    {
        try 
        {
            if (Yii::$app->users->getOne(Yii::$app->user->id)) 
            {
                if (Yii::$app->users->delAvatar(Yii::$app->user->id))
                {
                    return json_encode(['success' => 'Аватар удален']);
                }
                else
                {
                    throw new \Exception('Аватар не удален');
                }
            }
            else
            {
                throw new \Exception('Аватар не удален - не выбраны параметры');
            }
        }
        catch (\Exception $e) 
        {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    
     /**
     * Renders the del for the module
     * @return string
     */
    public function actionDel()
    {
        try 
        {
            if (Yii::$app->users->getOne(Yii::$app->user->id)) 
            {
                if (Yii::$app->users->del(Yii::$app->user->id))
                {
                    Yii::$app->getSession()->setFlash('success', 'Пользователь удален');
                    return $this->redirect(['/lk/']);
                }
                else
                {
                    throw new \Exception('Пользователь не удален');
                }
            }
            else
            {
                throw new \Exception('Пользователь не удален - не выбраны параметры');
            }
        }
        catch (\Exception $e) 
        {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['/lk/']);
        }
    }
}