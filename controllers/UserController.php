<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
            return false;

        switch ($action->id) {
           case 'index':
                if (Yii::$app->user->isGuest) {

                    return $this->goLogin();
                }
             break;
           case 'login':
                if (!Yii::$app->user->isGuest) {

                    return $this->goHome();
                }
             break;
           case 'logout':
                if (Yii::$app->user->isGuest) {

                    return $this->goLogin();
                }
             break;
//           case 'profile':
//
//             break;
//           case 'edit':
//
//             break;
        }

        return true;
    }

    /**
    * The default action of this module
    */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
    * The login action
    */
    public function actionLogin()
    {
        $model = Yii::$app->getModule('accounts')->getModel('login');
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        } else {

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
    * The logout action
    */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
    * The register action
    */
    public function actionSignup()
    {
        $model = Yii::$app->getModule('accounts')->getModel('signup');
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {

                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
    * Redirects the browser to the login page.
    *
    * @return Response the current response object
    */
    public function goLogin()
    {
        $this->redirect(['/accounts/user/login']);
    }

}
