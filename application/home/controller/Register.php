<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class Register extends Controller
{
    public function register(Request $request)
    {
        if ($request->method()=='POST'){
            $data=$request->param();
            $username=$request->param('username');
            $password=$request->param('password');
            //验证规则
//            $name=Db::table('user')->where('username',$username)->find();
////            var_dump($name) ;exit;
//            if ($name)
//            {
//                $this->error('用户名已存在,请使用其他用户名','/home/register/register');
//            }
            //Validate类来实现数据验证
            $rules=
            [
                'username'=>'require|length:2,10|unique:user',
                'password'=>'require|length:4,15|confirm:password2',

            ];
            $msg=
                [
                  'username.unique'=>'用户名已经存在',
                  'username.require'=>'用户名不能为空',
                  'password.require'=>'密码不能为空',
                  'username.length'=>'用户名长度仅限于2-10长度',
                  'password.length'=>'密码长度仅限于4-10长度',
                  'password.confirm'=>'两次输入密码不一致',
                ];
            $valid = new Validate($rules,$msg);
            if (!$valid->check($data)){
                $this->error($valid->getError());
            }
            $user['username']=$username;
            $user['password']=md5($password);

            Db::table('user')->insert($user);
            $this->redirect('/home/login/login');
        }
        elseif ($request->method()=='GET')
        {
          return view();
        }


    }
}