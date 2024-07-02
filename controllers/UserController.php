<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'idUser' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $idUser Id User
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idUser)
    {
        return $this->render('view', [
            'model' => $this->findModel($idUser),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new User();
    $model->scenario = User::SCENARIO_CREATE;

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'idUser' => $model->idUser]);
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}




    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idUser Id User
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idUser)
{
    $model = $this->findModel($idUser);

    if ($this->request->isPost && $model->load($this->request->post())) {
        // Cek apakah ada perubahan pada password
        if (!empty($model->password)) {
            // Hash password baru jika ada
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
        } else {
            // Gunakan password yang sudah ada di database
            $model->password = $model->getOldAttribute('password');
        }

        if ($model->save()) {
            return $this->redirect(['view', 'idUser' => $model->idUser]);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idUser Id User
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idUser)
    {
        $this->findModel($idUser)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idUser Id User
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idUser)
    {
        if (($model = User::findOne(['idUser' => $idUser])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
