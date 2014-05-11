<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

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
                        'actions' => ['login', 'signup', 'forgot-password', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'profile', 'logout'],
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
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays a single Account model.
     * @param string $id
     * @return mixed
     */
    public function actionProfile($id=null)
    {
        $myID = Yii::$app->user->identity->id;
        if ($id === null) {
            $id = $myID;
        }
        return $this->render('profile', [
            'model' => $this->findModel($id),
            'myID' => ($id == $myID) ? $myID : null,
        ]);
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
    * The password reset action
    *
    * @param string $token
    * @return {\yii\web\Response|Response|static|string}
    */
    public function actionResetPassword($token)
    {
        try {
            $modelPath = Yii::$app->getModule('accounts')->getModel('reset_password', false);
            $model = new $modelPath($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password has been saved. You can use it to login now.');

            return $this->goLogin(['accounts/user/index']);
        }

        return $this->render('reset_password', [
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
    * Redirects the browser to the login page. If the current page is not available after
    * login, please use following param.
    *
    * @param string|array $returnUrl the URL that the user should be redirected to after login.
    * If an array is given, [[UrlManager::createUrl()]] will be called to create the corresponding URL.
    * The first element of the array should be the route, and the rest of
    * the name-value pairs are GET parameters used to construct the URL. For example,
     *
     * ~~~
     * ['admin/index', 'ref' => 1]
     * ~~~
     *
    * @return Response the current response object
    */
    public function goLogin($returnUrl=null)
    {
        if ($returnUrl !== null) {
            Yii::$app->getUser()->setReturnUrl($returnUrl);
        }
        $this->redirect(['/accounts/user/login']);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);
        if (($model = $modelPath::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
