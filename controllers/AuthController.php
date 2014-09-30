<?php

namespace cakebake\accounts\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AuthController extends Controller
{
    /**
    * Testing...........
    */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        // add permissions
        $createAccount = $this->createAuthPermission('createAccount', 'Create an account');
        $readAccount = $this->createAuthPermission('readAccount', 'Read an account');
        $editAccount = $this->createAuthPermission('editAccount', 'Edit an account');
        $deleteAccount = $this->createAuthPermission('deleteAccount', 'Delete an account');

        // add role "user" and assign permissions
        $user = $this->createAuthRole('user');
        $this->assignAuthRolePermission($user, $readAccount);

        // add role "manager" and assign permissions
        $manager = $this->createAuthRole('manager');
        $this->assignAuthRolePermission($manager, [$user, $createAccount, $editAccount]);

        // add role "admin" and assign permissions
        $admin = $this->createAuthRole('admin');
        $this->assignAuthRolePermission($admin, [$manager, $deleteAccount]);

        //assign roles to user ids
        $this->assignAuthRoleToUser($manager, 2); //user
        $this->assignAuthRoleToUser($admin, 1); //admin



        return $this->render('index', [
        ]);
    }

    /**
    * Creates a new permission
    *
    * @param string $name
    * @param string $description
    * @return Permission
    */
    protected function createAuthPermission($name, $description)
    {
        $auth = Yii::$app->authManager;

        if (($permission = $auth->getPermission($name)) === null) {
            $permission = $auth->createPermission($name);
            $permission->description = $description;
            $auth->add($permission);
        }

        return $permission;
    }

    /**
    * Creates a new Role
    *
    * @param string $name
    * @return Role
    */
    protected function createAuthRole($name)
    {
        $auth = Yii::$app->authManager;

        if (($role = $auth->getRole($name)) === null) {
            $role = $auth->createRole($name);
            $auth->add($role);
        }

        return $role;
    }

    /**
    * Adds an item as a child of another item
    *
    * @param mixed $role
    * @param mixed $permission
    */
    protected function assignAuthRolePermission($role, $permission)
    {
        $auth = Yii::$app->authManager;

        if (!is_array($permission))
            $permission = [$permission];

        foreach ($permission as $perm) {
            if (!$auth->hasChild($role, $perm)) {
                $auth->addChild($role, $perm);
            }
        }
    }

    /**
    * Assign role to user
    *
    * @param Role $role
    * @param string|integer $userId the user ID (see [[\yii\web\User::id]])
    */
    protected function assignAuthRoleToUser($role, $userId)
    {
        $auth = Yii::$app->authManager;
        $assignments = $auth->getAssignments($userId);

        if (!isset($assignments[$role->name])) {
            $auth->assign($role, $userId);
        }
    }

}
