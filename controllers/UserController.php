<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;

class UserController extends Controller
{
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
                    $this->goLogin();
                }
             break;
           case 'login':

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
        return $this->render('login');
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
