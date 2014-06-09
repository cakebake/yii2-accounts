<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

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
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => Yii::$app->getModule('accounts')->enableLogin,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            throw new UnauthorizedHttpException(Yii::t('accounts', 'The login is currently disabled.'));
                        }
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => Yii::$app->getModule('accounts')->enableSignup,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            throw new UnauthorizedHttpException(Yii::t('accounts', 'The signup is currently disabled.'));
                        }
                    ],
                    [
                        'actions' => ['signup-activation'],
                        'allow' => (Yii::$app->getModule('accounts')->enableSignup && Yii::$app->getModule('accounts')->enableEmailSignupActivation) ? true : false,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            throw new UnauthorizedHttpException(Yii::t('accounts', 'The signup activation is currently disabled.'));
                        }
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
                'actions' => [
                    'signup-activation' => ['get'],
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
    * The login action
    */
    public function actionLogin()
    {
        $model = Yii::$app->getModule('accounts')->getModel('user', true, ['scenario' => 'login']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($user = $model->findUser($model->username)) !== null) {
                if (Yii::$app->user->login($user, ($model->rememberMe && Yii::$app->user->enableAutoLogin) ? 3600 * 24 * 30 : 0)) {

                    return $this->goBack();
                }
            }
        }

        return $this->render('login', [
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
        $model = Yii::$app->getModule('accounts')->getModel('user', true, ['scenario' => 'signup']);

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            return false;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->setAuthKey() && $model->setSignupUserConfig() && $model->save()) {
                if (Yii::$app->getModule('accounts')->enableEmailSignupActivation) {

                    $email = Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'signupActivation', ['user' => $model])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject(Yii::t('accounts', 'Account activation for {appname}', ['appname' => Yii::$app->name]))
                        ->send();

                    if ($email) {
                        Yii::$app->session->setFlash('success-signup', Yii::t('accounts', 'Registration was successful. Please check your email inbox for further action to account activation.'));
                    } else {
                        Yii::$app->session->setFlash('error-signup-email', Yii::t('accounts', 'Registration was successful, but the activation email could not be sent. Please contact us if you think this is a server error. Thank you.'));
                    }

                } else {

                    if (Yii::$app->getUser()->login($model)) {
                        return $this->goHome();
                    }

                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
    * The signup activation page
    * This page is only used with the registration email to activate a user account
    *
    * @param string $email The email adress from signup form
    * @param string $auth_key The generated auth_key from signup
    */
    public function actionSignupActivation($email, $auth_key)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);

        if (($model = $modelPath::findByEmail($email)) !== null) {
            if ($model->status === $modelPath::STATUS_INACTIVE) {
                if ($model->auth_key === $auth_key) {
                    $model->setScenario('signup-activation');
                    $model->setSignupActivationDefaults();
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success-activation', Yii::t('accounts', 'The activation was successful. You can login now.'));
                    }
                }
            } else {
                Yii::$app->session->setFlash('info-activation', Yii::t('accounts', 'Your account is already active. You can login now.'));
            }

            $this->goLogin(['/site/index']);
        }

        throw new BadRequestHttpException(Yii::t('accounts', 'Your account could not be activated. Please contact us if you think this is a server error. Thank you.'));
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
    public function goLogin($returnUrl = null)
    {
        if ($returnUrl !== null) {
            Yii::$app->getUser()->setReturnUrl($returnUrl);
        }

        $this->redirect(Yii::$app->user->loginUrl);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string|array $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);
        $model = null;

        if (is_array($id)) {
            $model = $modelPath::find()->where(['id' => $id])->all(); //@todo try ->batch()
        } else {
            $model = $modelPath::findOne($id);
        }

        if ($model === null)
            throw new NotFoundHttpException(Yii::t('accounts', 'The requested page does not exist.'));

        return $model;
    }

}
