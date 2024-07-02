<?php

namespace app\commands;

use yii\console\Controller;
use app\models\User;

class UserController extends Controller
{
    public function actionCreateTestUser()
    {
        $user = new User();
        $user->username = 'testuser';
        $user->password = \Yii::$app->security->generatePasswordHash('testpassword');
        if ($user->save()) {
            echo "User 'testuser' created successfully.\n";
        } else {
            echo "Error creating user.\n";
        }
    }
}
