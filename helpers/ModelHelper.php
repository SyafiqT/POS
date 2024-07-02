<?php

namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ModelHelper
{
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    public static function loadMultiple($models, $data, $formName = null)
    {
        if ($formName === null) {
            /* @var $model Model */
            $model = reset($models);
            if ($model === false) {
                return false;
            }
            $formName = $model->formName();
        }

        $success = false;
        foreach ($models as $i => $model) {
            if ($formName == '' && !empty($data[$i])) {
                $model->load($data[$i], '');
                $success = true;
            } elseif (isset($data[$formName][$i])) {
                $model->load($data[$formName][$i], '');
                $success = true;
            }
        }

        return $success;
    }

    public static function validateMultiple($models)
    {
        $valid = true;
        foreach ($models as $model) {
            $valid = $model->validate() && $valid;
        }
        return $valid;
    }
}
