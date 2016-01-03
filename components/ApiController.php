<?php

class ApiController extends GxController
{
    public $layout = false;

    protected function _sendResponse($body = null, $status = 200, $itemCount = null)
    {
        $httpStatus = new EHttpStatus($status);

        header($httpStatus);
        header('Content-type: application/json');
        if($itemCount != null) {
            header('X-Item-Count: ' . $itemCount);
        }

        if ($body !== null) {
            echo CJSON::encode($body);
        }

        Yii::app()->end();
    }

    public function loadModel($key, $modelClass)
    {
        if (is_array($key)) {
            return parent::loadModel($key, $modelClass);
        }

        $staticModel = GxActiveRecord::model($modelClass);
        $model = $staticModel->findByPk($key);

        if (!$model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    protected function extractErrors(CActiveRecord $model)
    {
        $str = '';
        if($model->errors) {
            foreach ($model->errors as $attr => $errors) {
                $str .= " " . implode(" ", $errors);
            }
        }

        return preg_replace("/\s+/", ' ', trim($str));
    }
} 