<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public static function tableName()
    {
        return 'users'; // Pastikan sesuai dengan nama tabel yang digunakan
    }

    public function rules()
    {
        return [
            [['username', 'role'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password'], 'required', 'on' => self::SCENARIO_CREATE],
            [['password'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['role'], 'in', 'range' => ['viewer', 'admin', 'super_admin']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['username', 'password', 'role'];
        $scenarios[self::SCENARIO_UPDATE] = ['username',  'role']; // Password not required
        return $scenarios;
    }

    public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        // Hash password jika diisi dan record baru
        if (!empty($this->password) && $insert) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        } elseif (empty($this->password) && !$insert) {
            // Jika password kosong, gunakan password lama (untuk update)
            $this->password = $this->oldAttributes['password'];
        }
        return true;
    }
    return false;
}


    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
{
    return Yii::$app->security->validatePassword($password, $this->password);
}



    // Implement methods for IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Your logic here if you use access tokens
    }

    public function getId()
    {
        return $this->idUser;
    }

    public function getAuthKey()
    {
        // Your logic here if you use auth keys
    }

    public function validateAuthKey($authKey)
    {
        // Your logic here if you use auth keys
    }
}
