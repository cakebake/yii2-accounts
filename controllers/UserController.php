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
                //'only' => ['login', 'logout', 'signup', 'forgot-password'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'forgot-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout'],
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

//        switch ($action->id) {
//           case 'index':
//                if (Yii::$app->user->isGuest) {
//
//                    return $this->goLogin();
//                }
//             break;
//           case 'logout':
//                if (Yii::$app->user->isGuest) {
//
//                    return $this->goLogin();
//                }
//             break;
//        }

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
    * The password forgotten action
    */
    public function actionForgotPassword()
    {
        $model = Yii::$app->getModule('accounts')->getModel('forgot_password');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('forgot_password', [
            'model' => $model,
        ]);
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
