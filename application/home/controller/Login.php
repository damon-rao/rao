<?php
namespace app\home\controller;
use app\home\model\User;
use think\Db;
use think\Request;

class Login extends \think\Controller
{
    public function login(Request $request)
    {

        if ($request->method()=='GET')
        {
            return view();
        }
        if ($request->method()=='POST')
        {
            $data=$request->param();
            $username=$request->param('username');
            $password=$request->param('password');
//             echo $password;exit;
            $user=Db::table('user')->where('username',$username)->find();
//            $pwd=Db::table('user')->where('password',md5($password))->find();
//            var_dump($user['password']);exit;
            if($user)
            {
                if ($user['password']==md5($password))
                {
                    //拿到当前用户id 的值，传session。
                    $id=User::where('username',$username)->column('id')[0];

                    session_start();
                    $_SESSION['username']=$username;
                    $_SESSION['id']=$id;
                    //这样的设置前端{$Request.session.username}会得到色孙思邈值
                    session('username',$username);

//                    session('id',$id);
//                    echo $_SESSION['username'];
//                    echo 'mysql:'.$user['password'],'pass:'.md5($password);exit;
                    $this->success('登录成功','/home/index/index');
                }

            }
                return view();
        }


    }

    public function logout()
    {
       session('username',null);
       $_SESSION['username']=null;
        $this->redirect('/home/index/index');
    }

}

