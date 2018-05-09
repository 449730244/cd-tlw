<?php
/**
 * Created by PhpStorm.
 * User: aliez
 * Date: 07/11/2017
 * Time: 17:14
 * 菜单管理
 */
namespace app\admin\controller;

use think\Db;
use think\Validate;

class Menu extends Common
{
    public function _initialize()
    {
        return parent::_initialize(); // TODO: Change the autogenerated stub
    }

    // 菜单导航
    function navList()
    {
        $list = DB::name('category')->getTreeData('tree','id','name');
        $this->assign('list',$list);
        return view();
    }

    // 添加菜单d导航
    public function  navAdd()
    {
        if(request()->isPost())
        {
            $validate = validate('Menu');
            $data = input('post.');
            if(!$validate->check($data))
            {
                return ['code' => 1,'msg' => $validate->getError()];
            }else{
                if(Db::name('category')->insert($data))
                {
                    return ['url'=>'/admin/Menu/navList','code'=>0,'msg'=>'添加成功'];
                }
            }
        }
        if(input('param.pid'))
        {
            $pid = input('param.pid');
            $p_name = Db::name('category')->field('name')->where(['id' => $pid])->find();

            $this->assign('p_name',$p_name['name']);
            $this->assign('pid',$pid);
        }
        return view();
    }

     //修改菜单导航
    public function navEdit()
    {
        if(request()->isPost())
        {
            $validate = validate('Menu');
            $data = input('post.');
            if(!$validate->check($data))
            {
                return ['code' => 1,'msg' => $validate->getError()];
            }else{
                if(Db::name('category')->where(['id' => $data['id']])->update($data))
                {
                    return ['url'=>'/admin/Menu/navList','code'=>0,'msg'=>'修改成功'];
                }
            }
        }
        $cid = input('param.cid');
        $cate = Db::name('category')->where(['id' => $cid])->find();
        $this->assign('cate',$cate);
        return view();
    }


    //删除分类导航
    public function navDel($cid)
    {
        if($this->checkChild('category',['pid' => $cid]))
        {
            return ['code' => 0,'msg' => '存在子分类，无法删除'];
        }else{
            if(Db::name('category')->where(['id' => $cid])->delete())
            {
                return ['url'=>'/admin/Menu/navList','code'=>0,'msg'=>'删除成功'];
            }
        }
    }

    //检测权限是否有子权限
    public function checkChild($table,$where)
    {
        if(Db::name($table)->where($where)->count() > 0)
        {
            return true;
        }
        return false;
    }
}