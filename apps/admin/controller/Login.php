<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7
 * Time: 17:42
 */
namespace app\admin\Controller;

use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{
    //登录
    public function login()
    {
        if(request()->isPost())
        {
            if($user = $this->checkUser())
            {
                Session::set('current_user',$user);
                return ['url'=>'/admin/Index/index','code'=>1,'msg'=>'登录成功。'];
            }else{
                return ['code'=>0,'msg'=>'账号或密码错误，请从新登录。'];
            }
        }
        return view('login/login');
    }

    //退出
    public function loginOut()
    {
        Session::clear();
        $this->redirect('Login/login');
    }


    public function checkUser()
    {
        $where = array(
            'username' => input('post.username'),
            'password' => encrypt(input('post.password'))
        );
        $user = Db::name('admin')->where($where)->find();
        if($user)
        {
            return $user;
        }
        return false;
    }

}