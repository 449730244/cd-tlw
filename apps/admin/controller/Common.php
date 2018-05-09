<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7
 * Time: 15:03
 */
//公共控制器
namespace app\admin\Controller;

use think\Controller;
use think\Session;
use Auth\Auth;

class Common extends Controller
{
    //初始化
    public function _initialize()
    {
        if($this->checkLogin())
        {
            $user = Session::get('current_user');
            //获取菜单
            $menu = getMenuList($user['id']);
            //模板——控制器——方法
            $rule = request()->module() . '/' . request()->controller() . '/' . request()->action();

            $this->assign('menu',$menu);
            $this->assign('url',$rule);

            //判断是否是超管
            if($user['is_super'] != 1)
            {
                //权限认证
                $auth = new Auth();
                if(!$auth->check($rule, $user['id']))
                {
                    return ['code'=>0,'msg'=>'没有操作权限'];
                }
            }
        }else{
            $this->redirect('Login/login');
        }
    }

    //是否登录
    public function checkLogin()
    {
        if(Session::has('current_user')){
            if(Session::get('current_user'))
            {
                return true;
            }
        }
        return false;
    }
}