<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RolesForm;

class PermissionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有权限
        $auth = \Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        return $this->render('index',['permissions'=>$permissions]);
    }

    public function actionAdd(){

        //添加权限
        $auth = \Yii::$app->authManager;
        $permission_form=new PermissionForm();
        //定义场景
       // $permission_form->scenario='add';
        //接收数据
        $request=\Yii::$app->request;
        if($request->isPost){
            $permission_form->load($request->post());
            $permission_form->status=1;
            if($permission_form->validate()){
                //调用组件保存权限
                $permission=$auth->createPermission($permission_form->name);
                //添加描述
                $permission->description=$permission_form->description;
                $auth->add($permission);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
//        $permission_form->name = '123';
        return $this->render('add',['permission_form'=>$permission_form]);
    }

    public function actionEdit(){

        //修改权限
        $request=\Yii::$app->request;
        $name=$request->get('name');
        $auth = \Yii::$app->authManager;
        $permission_form=new PermissionForm();

        $permission_form->name=$name;
        //根据name查询描述
        $permission_form->description=$auth->getPermission($name)->description;

        //接收表单数据
        if($request->isPost){
            //判断是否修改,如果没有修改
            $permission_form->load($request->post());

                //修改了权限
                $permission_form->status=$permission_form->name==$name?0:1;
                if($permission_form->validate()){
                    //修改权限

                    $old=$auth->createPermission($permission_form->name);

                    $old->description=$permission_form->description;
                    $auth->update($name,$old);

                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['index']);
                }


        }
        return $this->render('add',['permission_form'=>$permission_form]);
    }

    public function actionDelete(){

        //删除权限
        $request=\Yii::$app->request;
        $name=$request->get('name');
        //根据name删除权限
        $auth = \Yii::$app->authManager;
        $permission=$auth->getPermission($name);
        $auth->remove($permission);
        return $this->redirect(['index']);
    }

    public function actionAddRoles(){

        //添加角色
        $request=\Yii::$app->request;
        $roles_form=new RolesForm();

        if($request->isPost){
            $roles_form->load($request->post());
            $roles_form->status=1;
            if($roles_form->validate()){
                //添加角色
               $auth= \Yii::$app->authManager;
               $role=$auth->createRole($roles_form->name);
               //添加描述
                $role->description=$roles_form->description;
                //保存角色
                $auth->add($role);
                //分配权限
                // ['user/add','goods/edit']
                if($roles_form->permissions){
                    foreach ($roles_form->permissions as $permissionName){
                        //获取权限对象
                        $permission=$auth->getPermission($permissionName);
                        $auth->addChild($role,$permission);
                    }
                }

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['roles-index']);
            }
        }
        return $this->render('add-roles',['roles_form'=>$roles_form]);
    }

    public function actionRolesIndex(){

        //角色列表功能
        $auth = \Yii::$app->authManager;
        //获取所有角色列表
        $roles=$auth->getRoles();
        return $this->render('roles-index',['roles'=>$roles]);
    }

    public function actionRolesDelete(){

        //删除角色
        $request=\Yii::$app->request;
        $name=$request->get('name');
        $auth = \Yii::$app->authManager;
        $auth->remove($auth->getRole($name));
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['roles-index']);
    }

    public function actionRolesEdit(){

        //修改角色
        $request=\Yii::$app->request;
        $name=$request->get('name');
        $auth = \Yii::$app->authManager;
        $roles_form=new RolesForm();

        $roles_form->name=$name;
        $roles_form->description=$auth->getRole($name)->description;
        //回显权限
/*        $roles=[];
        foreach ($auth->getPermissionsByRole($name) as $permission){
            $roles[$permission->name]=$permission->description;
        }*/

        $roles_form->permissions=array_keys($auth->getPermissionsByRole($name));

        if($request->isPost){
            $roles_form->load($request->post());
            //判断是否修改了角色名称
            $roles_form->status=$roles_form->name==$name?0:1;
            $auth= \Yii::$app->authManager;
                if($roles_form->validate()){
                    //修改了角色
                    $role=$auth->createRole($roles_form->name);
                    //修改描述
                    $role->description=$roles_form->description;
                    //保存角色
                    $auth->update($name,$role);
                    //分配权限
                    if($roles_form->permissions){
                        //取消所有权限并根据表单从新分配
                        $auth->removeChildren($auth->getRole($roles_form->name));

                        foreach ($roles_form->permissions as $permissionName){
                            //获取权限对象
                            $permission=$auth->getPermission($permissionName);
                            $auth->addChild($role,$permission);
                        }
                    }else{
                        //取消该用户所有权限
                        $auth->removeChildren($auth->getRole($roles_form->name));
                    }

                    return $this->redirect(['roles-index']);
                }
            }

        return $this->render('add-roles',['roles_form'=>$roles_form]);
    }
}
