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
use cakebake\actionlog\model\ActionLog;

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
                        'actions' => ['login', 'forgot-password', 'reset-password'],
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
                        'actions' => ['signup-activation', 'signup-activation-resend'],
                        'allow' => (Yii::$app->getModule('accounts')->enableSignup && Yii::$app->getModule('accounts')->enableEmailSignupActivation) ? true : false,
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            throw new UnauthorizedHttpException(Yii::t('accounts', 'The signup activation is currently disabled.'));
                        }
                    ],
                    [
                        'actions' => ['logout', 'profile', 'edit', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
                    //'signup-activation' => ['get'],
                ],
//                'actions' => [
//                    'signup-activation' => ['get'],
//                ],
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
     * Displays a single profile
     *
     * @param string $u The user name
     * @return mixed
     */
    public function actionProfile($u)
    {
        $model = Yii::$app->getModule('accounts')->getModel('user', false);

        if (($user = $model::findByUsername($u)) === null) {
            throw new NotFoundHttpException(Yii::t('accounts', 'The requested page does not exist.'));
        }

        return $this->render('profile', [
            'model' => $user,
        ]);
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
            if ($model->setSignupUserConfig() && $model->save()) {

                $logInfo = [
                    'info' => $model->username . ' has successfully registered.',
                    'username' => $model->username,
                    'email' => $model->email,
                    'role' => $model->role,
                    'status' => $model->status,
                ];

                if (Yii::$app->getModule('accounts')->enableEmailSignupActivation) {

                    $email = Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'signupActivation', ['user' => $model])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject(Yii::t('accounts', 'Account activation for {appname}', ['appname' => Yii::$app->name]))
                        ->send();

                    $logInfo['email_activation'] = [
                        'auth_key' => $model->auth_key,
                        'send_from' => Yii::$app->params['supportEmail'],
                        'send_to' => $model->email,
                    ];

                    if ($email) {
                        $logInfo['email_activation']['send_status'] = 'success';
                        Yii::$app->session->setFlash('success-signup-email', Yii::t('accounts', 'Registration was successful. Please check your email inbox for further action to account activation.'));
                    } else {
                        $logInfo['email_activation']['send_status'] = 'error';
                        Yii::$app->session->setFlash('error-signup-email', Yii::t('accounts', 'Registration was successful, but the activation email could not be sent. Please contact us if you think this is a server error. Thank you.'));
                    }

                    $this->goLogin(['/site/index']);

                } else {

                    $logInfo['email_activation'] = 'disabled';

                    if (Yii::$app->getUser()->login($model)) {
                        $logInfo['auto_login'] = 'success';
                        Yii::$app->session->setFlash('success-signup', Yii::t('accounts', 'Registration was successful.'));

                        return $this->redirect(['profile', 'u' => $model->username]);
                    } else {
                        $logInfo['auto_login'] = 'error';
                        Yii::$app->session->setFlash('error-signup', Yii::t('accounts', 'Registration failed. Please contact us if you think this is a server error. Thank you.'));

                        return $this->goLogin(['/site/index']);
                    }

                }

                ActionLog::add(ActionLog::LOG_STATUS_INFO, $logInfo, $model->id);

            } else {
                ActionLog::add(ActionLog::LOG_STATUS_ERROR, [
                    'username' => $this->username,
                    'email' => $this->email,
                    'errors' => $user->errors,
                ]);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Edits a single profile
     *
     * @param string $u The user name
     * @return mixed
     */
    public function actionEdit($u)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);

        if (($model = $modelPath::findByUsername($u)) === null) {
            throw new NotFoundHttpException(Yii::t('accounts', 'The requested page does not exist.'));
        }

        $model->setScenario('edit');

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            return false;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $identityChange = false;
            $oldAttributes = $model->oldAttributes;
            if ($model->username != $oldAttributes['username']) {
                $identityChange = true;
            }
            if ($model->email != $oldAttributes['email']) {
                $identityChange = true;
            }

            if ($identityChange && Yii::$app->getModule('accounts')->enableEmailEditActivation) {
                if ($model->setAuthKey() && $model->setEditUserConfig() && $model->save(false)) {

                    $email = Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'editActivation', ['user' => $model])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($model->email)
                        ->setSubject(Yii::t('accounts', 'Account activation for {appname}', ['appname' => Yii::$app->name]))
                        ->send();

//                    if ($email) {
//                        Yii::$app->user->logout();
//                        Yii::$app->session->setFlash('success-edit', Yii::t('accounts', 'Update was successful. Please check your email inbox for further action to account activation.'));
//
//                        return $this->goLogin(['/site/index']);
//                    } else {
//                        Yii::$app->session->setFlash('error-edit-email', Yii::t('accounts', 'Because the activation email could not be sent, we restored the current settings. Please contact us if you think this is a server error. Thank you.'));
//                        $model->restoreEditUserConfig($oldAttributes);
//
//                        return $this->redirect(['profile', 'u' => $model->username]);
//                    }

                    //test
                    Yii::$app->session->setFlash('error-edit-email', Yii::t('accounts', 'Test redirect... email'));
                    return $this->redirect(['profile', 'u' => $model->username]);
                    //test

                }
            } else {
                if ($model->save(false)) {
                    return $this->redirect(['profile', 'u' => $model->username]);
                }
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $u The user name
     * @return mixed
     */
    public function actionDelete($u)
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);

        if (($model = $modelPath::findByUsername($u)) === null) {
            throw new NotFoundHttpException(Yii::t('accounts', 'The requested page does not exist.'));
        }

        $model->setScenario('delete');

        $model->delete();

        return $this->redirect(['index']);
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
                Yii::$app->session->setFlash('info-activation', Yii::t('accounts', 'Your account is already active.'));
            }

            $this->goLogin(['/site/index']);
        }

        throw new BadRequestHttpException(Yii::t('accounts', 'Your account could not be activated. Please contact us if you think this is a server error. Thank you.'));
    }

    /**
    * Resends the activation email
    */
    public function actionSignupActivationResend() {
        $model = Yii::$app->getModule('accounts')->getModel('user', true, ['scenario' => 'signup-activation-resend']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($user = $model::findByEmail($model->email)) !== null) {
                if ($user->status === $model::STATUS_INACTIVE) {

                    $email = Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'signupActivation', ['user' => $user])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($user->email)
                        ->setSubject(Yii::t('accounts', 'Account activation for {appname}', ['appname' => Yii::$app->name]))
                        ->send();

                    if ($email) {
                        Yii::$app->session->setFlash('success-signup-activation-resend', Yii::t('accounts', 'Please check your email inbox for further action to account activation.'));
                    } else {
                        Yii::$app->session->setFlash('error-signup-activation-resend-email', Yii::t('accounts', 'The activation email could not be sent. Please contact us if you think this is a server error. Thank you.'));
                    }

                } else {
                    Yii::$app->session->setFlash('info-activation', Yii::t('accounts', 'Your account is already active.'));
                }
                $this->goLogin(['/site/index']);
            }
            throw new BadRequestHttpException(Yii::t('accounts', 'Your account can not be activated. Please contact us if you think this is a server error. Thank you.'));
        }

        return $this->render('signupActivationResend', [
            'model' => $model,
        ]);
    }

    /**
    * The password forgotten action
    */
    public function actionForgotPassword()
    {
        $model = Yii::$app->getModule('accounts')->getModel('user', true, ['scenario' => 'forgot-password']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($user = $model::findActiveByEmail($model->email)) !== null) {

                $user->generatePasswordResetToken();
                $user->setScenario('generate-password-reset-token');

                if ($user->save()) {

                    $email = Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'forgotPassword', ['user' => $user])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                        ->setTo($user->email)
                        ->setSubject(Yii::t('accounts', 'Password reset for {appname}', ['appname' => Yii::$app->name]))
                        ->send();

                    if ($email) {
                        Yii::$app->getSession()->setFlash('success-forgot-password', Yii::t('accounts', 'Please check your email for further instructions.'));
                    } else {
                        Yii::$app->session->setFlash('error-forgot-password', Yii::t('accounts', 'Sorry, we are unable to reset password for email provided. Please contact us if you think this is a server error. Thank you.'));
                    }
                    $this->goLogin(['/site/index']);
                }
            }
            throw new BadRequestHttpException(Yii::t('accounts', 'Sorry, we are unable to reset password for your account. Please contact us if you think this is a server error. Thank you.'));
        }

        return $this->render('forgotPassword', [
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
        $model = Yii::$app->getModule('accounts')->getModel('user', true, ['scenario' => 'reset-password']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (($user = $model::findByPasswordResetToken($token)) !== null) {

                if ($user->setResetPasswordDefaults($model->password)) {
                    Yii::$app->getSession()->setFlash('success-reset-password', Yii::t('accounts', 'The new password has been saved successfully. You can use it to login now.'));

                    $this->goLogin(['/site/index']);
                }

            }
            throw new BadRequestHttpException(Yii::t('accounts', 'Sorry, we are unable to reset password for your account. Please contact us if you think this is a server error. Thank you.'));
        }

        return $this->render('resetPassword', [
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
