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
//        $auth = Yii::$app->authManager;



//        // add owner rule
//        $ownerRule = $this->createAuthRule('\cakebake\accounts\components\OwnerRule');
//
//        // add owner permissions
//        $editCreatedAccount = $this->createAuthPermission('editCreatedAccount', 'Edit Created Account', $ownerRule->name);
//        $deleteCreatedAccount = $this->createAuthPermission('deleteCreatedAccount', 'Delete Created Account', $ownerRule->name);
//
//        // add default permissions
//        $createAccount = $this->createAuthPermission('createAccount', 'Create Account');
//        $readAccount = $this->createAuthPermission('readAccount', 'Read Account');
//        $editAccount = $this->createAuthPermission('editAccount', 'Edit Account', null, $editCreatedAccount);
//        $deleteAccount = $this->createAuthPermission('deleteAccount', 'Delete Account', null, $deleteCreatedAccount);
//
//        // add role "user" and assign permissions
//        $user = $this->createAuthRole('user', 'User');
//        $this->assignAuthRolePermission($user, $readAccount);
//
//        // add role "manager" and assign permissions
//        $redakteur = $this->createAuthRole('redakteur', 'Redakteur');
//        $this->assignAuthRolePermission($redakteur, [$user, $createAccount, $editAccount]);
//
//        // add role "manager" and assign permissions
//        $manager = $this->createAuthRole('manager', 'Manager');
//        $this->assignAuthRolePermission($manager, [$redakteur, $deleteCreatedAccount]);
//
//        // add role "admin" and assign permissions
//        $admin = $this->createAuthRole('admin', 'Admin');
//        $this->assignAuthRolePermission($admin, [$manager, $deleteAccount]);
//
//        //assign roles to user ids
//        $this->assignAuthRoleToUser($redakteur, 2); //user
//        $this->assignAuthRoleToUser($admin, 1); //admin




        return $this->render('index', [
        ]);
    }
//
//    /**
//    * Creates a new permission
//    *
//    * @param string $name
//    * @param string $description
//    * @param string $ruleName
//    * @param array $parentPermissions
//    * @return Permission
//    */
//    public function createAuthPermission($name, $description = null, $ruleName = null, $parentPermissions = null)
//    {
//        $auth = Yii::$app->authManager;
//
//        if (($permission = $auth->getPermission($name)) === null) {
//            $permission = $auth->createPermission($name);
//            if ($description !== null) {
//                $permission->description = $description;
//            }
//            if ($ruleName !== null) {
//                $permission->ruleName = $ruleName;
//            }
//            $auth->add($permission);
//        }
//
//        if ($parentPermissions !== null) {
//            if (!is_array($parentPermissions)) {
//                $parentPermissions = [$parentPermissions];
//            }
//            foreach ($parentPermissions as $parentPerm) {
//                if (($auth->getPermission($parentPerm->name) !== null) && !$auth->hasChild($parentPerm, $permission)) {
//                    $auth->addChild($parentPerm, $permission);
//                }
//            }
//        }
//
//        return $permission;
//    }
//
//    /**
//    * Creates a new Role
//    *
//    * @param string $name
//    * @return Role
//    */
//    public function createAuthRole($name, $description = null)
//    {
//        $auth = Yii::$app->authManager;
//
//        if (($role = $auth->getRole($name)) === null) {
//            $role = $auth->createRole($name);
//            if ($description !== null) {
//                $role->description = $description;
//            }
//            $auth->add($role);
//        }
//
//        return $role;
//    }
//
//    /**
//    * Creates a rule by its rule class
//    *
//    * @param string $ruleClass
//    * @return Rule
//    */
//    public function createAuthRule($ruleClass)
//    {
//        $auth = Yii::$app->authManager;
//        $ruleObj = is_string($ruleClass) ? new $ruleClass : $ruleClass;
//
//        if ($auth->getRule($ruleObj->name) === null) {
//            $auth->add($ruleObj);
//        }
//
//        return $ruleObj;
//    }
//
//    /**
//    * Adds an item as a child of another item
//    *
//    * @param mixed $role
//    * @param mixed $permission
//    */
//    public function assignAuthRolePermission($role, $permission)
//    {
//        $auth = Yii::$app->authManager;
//
//        if (!is_array($permission))
//            $permission = [$permission];
//
//        foreach ($permission as $perm) {
//            if (!$auth->hasChild($role, $perm)) {
//                $auth->addChild($role, $perm);
//            }
//        }
//    }
//
//    /**
//    * Assign role to user
//    *
//    * @param Role $role
//    * @param string|integer $userId the user ID (see [[\yii\web\User::id]])
//    */
//    public function assignAuthRoleToUser($role, $userId)
//    {
//        $auth = Yii::$app->authManager;
//        $assignments = $auth->getAssignments($userId);
//
//        if (!isset($assignments[$role->name])) {
//            $auth->assign($role, $userId);
//        }
//    }
//
//

}
