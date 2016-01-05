<?php

class UserController extends ApiController
{
//    public function filters()
//    {
//        return array(
//            'accessControl',
//        );
//    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id)
    {
        /** @var User $model */
        $model = User::model()->findShardedDataByUserId($id, 'id');

        if(!$model) {
            throw new CHttpException(404);
        }

        $this->_sendResponse(User::toDto($model));
    }

    public function actionCreate()
    {
        /** @var User $model */
        $model = new User();

        if (Yii::app()->request->isPostRequest) {
            $postdata = file_get_contents("php://input");
            $data = CJSON::decode($postdata, true);
            $model->setAttributes($data);

            if ($model->save()) {
                $this->_sendResponse($model);
            } else {
                throw new CHttpException(400, $this->extractErrors($model));
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, 'User');

        if (Yii::app()->request->isPostRequest) {
            $postdata = file_get_contents("php://input");
            $data = CJSON::decode($postdata, true);
            $model->setAttributes($data);
            $model->id = $id;

            if ($model->save()) {
                $this->_sendResponse($model);
            } else {
                throw new CHttpException(400, $this->extractErrors($model));
            }
        }
    }

    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsDeleteRequest()) {
            $this->loadModel($id, 'User')->delete();
        }
    }

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('User');
        $this->_sendResponse($dataProvider->getData());
    }

    public function actionList()
    {
        $model = new User('search');
        $model->unsetAttributes();

        if (isset($_GET['User'])) {
            $model->setAttributes($_GET['User']);
        }

        $dataProvider = $model->search();
        if (isset($_GET['page']) && preg_match("/^\d+$/", $_GET['page'])) {
            $dataProvider->pagination->setCurrentPage($_GET['page'] - 1);
        }

        $this->_sendResponse($dataProvider->getData(), 200, $dataProvider->pagination->getItemCount());
    }

}
