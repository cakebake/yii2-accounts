<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'delete-selected'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'delete-selected' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::$app->getModule('accounts')->getModel('admin_search');
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Admin model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = Yii::$app->getModule('accounts')->getModel('admin');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
    * Deletes one or more existring Admin models
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @return {\yii\web\Response|Response|static}
    */
    public function actionDeleteSelected()
    {
        $post = Yii::$app->request->post();

        if (empty($post) || !isset($post['ids']) || !is_array($post['ids']) || empty($post['ids']))
            return $this->redirect(['index']);

        $models = $this->findModel($post['ids']);

        foreach ($models as $k => $model) {
            if ($model->delete()) {
                Yii::$app->getSession()->setFlash('success-'.$k, Yii::t('accounts', '{nicename}´s account has been deleted successfully.', [
                    'nicename' => $model->getNicename(),
                ]));
            } else {
                Yii::$app->getSession()->setFlash('error-'.$k, Yii::t('accounts', 'Sorry, we are unable to delete account for user {nicename}.', [
                    'nicename' => $model->getNicename(),
                ]));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('admin', false);

        if (is_array($id)) {
            $model = $modelPath::find()->where(['id' => $id])->all();
        } else {
            $model = $modelPath::findOne($id);
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('accounts', 'The requested page does not exist.'));
        }
    }
}
