<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use Auth\Auth;
//获取所有菜单
function getAllMenu()
{
    //读取用作菜单显示的
    $cate = \think\Db::name('auth_rule')->where('is_menu = 1')->order('id asc')->select();
    $menu = getMenu($cate);
    return $menu;
}

//返回所有菜单
function getMenu($cate, $name = 'child', $pid = 0)
{
    $arr = array();
    foreach ($cate as $v)
    {
        if ($v['pid'] == $pid) {
            $v[$name] = getMenu($cate, $name, $v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

//根据权限获取菜单
function getMenuList($auth_id)
{
    $menu_list = getAllMenu();
    //dump($menu_list);
    //超级管理员加载所有菜单
    if($auth_id != '1'){
        //获取用户需要验证的所有有效规则列表
        $auth        = new Auth();
        $authList    = $auth->getMenuAuthList($auth_id,1);
        foreach($menu_list as $k => $v)
        {
            if(!in_array($v['id'], $authList))
            {    //判断数组是否在菜单表里面
                unset($menu_list[$k]);//过滤菜单
            }
            foreach ($v['child'] as $kk => $vv)
            {
                if(!in_array($vv['id'], $authList))
                {    //判断数组是否在菜单表里面
                    unset($menu_list[$k]['child'][$kk]);//过滤菜单
                }
            }
        }
    }
    return $menu_list;
}
//加密
function encrypt($str)
{
    return   md5(md5($str).config('hash'));
}