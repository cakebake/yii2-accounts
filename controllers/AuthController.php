<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * AuthController implements the CRUD actions for AccountAuthItem model.
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AccountAuthItem models
     *
     * @param null|string|integer $type The auth item type
     * @return mixed
     */
    public function actionIndex($type = null)
    {
        $searchModel = Yii::$app->getModule('accounts')->getModel('auth_item', true, ['scenario' => 'search']);
        $type = ($type === null) ? $searchModel::TYPE_ROLE : $type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Creates a new AccountAuthItem model.
     *
     * @param string|integer $type The auth item type
     * @return mixed
     */
    public function actionCreate($type)
    {
        $model = Yii::$app->getModule('accounts')->getModel('auth_item', true, ['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $auth = Yii::$app->authManager;
            switch ($type) {
                case $model::TYPE_ROLE:
                    $item = $auth->createRole($model->name);
                    break;
                case $model::TYPE_PERMISSION:
                    $item = $auth->createPermission($model->name);
                    break;
            }
            $item->description = $model->description;
            $auth->add($item);

            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type,
            ]);
        }
    }

    /**
     * Updates an existing AccountAuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        switch ($model->type) {
            case $model::TYPE_ROLE:
                $possibleChildren = new ArrayDataProvider([
                    'allModels' => $model->allPermissions,
                    'sort' => [
                        'attributes' => ['name', 'createdAt'],
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]);
                break;
        }

        if ($model->load($post = Yii::$app->request->post()) && $model->save()) {
            $model->updateChildren(isset($post['assignedChildren']) ? $post['assignedChildren'] : []);

            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'possibleChildren' => $possibleChildren,
            ]);
        }
    }

    /**
     * Displays a single AccountAuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $assigned = [];
        switch ($model->type) {
            case $model::TYPE_ROLE:
                $assigned['permissions'] = new ArrayDataProvider([
                    'allModels' => $model->permissionsByRole,
                    'sort' => [
                        'attributes' => ['name', 'createdAt'],
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]);
                break;
        }

        return $this->render('view', [
            'model' => $model,
            'assigned' => $assigned,
        ]);
    }

    /**
     * Deletes an existing AccountAuthItem model.
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
     * Finds the AccountAuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AccountAuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('auth_item', false);
        if (($model = $modelPath::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
