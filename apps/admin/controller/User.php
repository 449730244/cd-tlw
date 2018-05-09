<?php
/**
 * Created by PhpStorm.
 * User: aliez
 * Date: 07/11/2017
 * Time: 16:27
 * 用户管理
 */
namespace app\admin\controller;

use  think\Db;

class User extends Common
{

    //管理员
    public function userList()
    {
        $list = DB::name('admin a')
            ->field('a.id,a.username,a.status,a.create_time,a.login_count,a.last_login_ip,GROUP_CONCAT(g.title) title,a.is_super')
            ->join('auth_group_access ga','a.id = ga.uid')
            ->join('auth_group g','g.id = ga.group_id')
            ->group('a.id')
            ->paginate(10);
        $this->assign('list',$list);
        return view();
    }


    //管理员添加
    public function userAdd()
    {
        if(request()->isPost())
        {
            $data['username'] = input('post.username');
            $data['password'] = input('post.password');
            $data['status']   = input('post.status');
            $data['create_time'] = date('Y-m-d H:i:s');

            if(empty(input('post.group_id/a')))
            {
                return ['code'=>1,'msg'=>'请至少选择一个用户组'];
            }
            $validate = validate('User');
            if(!$validate->check($data))
            {
                return ['code' => 1,'msg' => $validate->getError()];
            }else{
                if($this->checkIsExist('admin',array('username'=>$data['username'])))
                {
                    return ['code' => 1,'msg' => '用户名已存在'];
                }else{

                    //密码加密
                    $data['password'] = encrypt(input('post.password'));

                    if($uid = Db::name('admin')->insertGetId($data))
                    {
                        $group_ids = input('post.group_id/a');

                        foreach ($group_ids as $key => $val)
                        {
                            $group_access[] = ['uid' => $uid,'group_id'=> $val];

                        }
                        if(Db::name('auth_group_access')->insertAll($group_access))
                        {
                            return ['url'=>'/admin/User/userList','code'=>0,'msg'=>'添加成功'];
                        }
                    }
                }

            }
        }
        $group_list = Db::name('auth_group')->where(array('status'=>1))->select();
        $this->assign('group_list',$group_list);
        return view();
    }

    //管理员编辑
    public function userEdit()
    {
        if(request()->isPost())
        {
            $uid              = input('post.id');
            $data['status']   = input('post.status');
            $group_ids        = input('post.group_id/a');
            if(empty($group_ids))
            {
                return ['code'=>1,'msg'=>'请至少选择一个用户组'];
            }
            $pass = input('post.password');
            if(!empty($pass))
            {
                //如果不为空，密码加密
                $data['password'] = encrypt($pass);
            }
            foreach ($group_ids as $key => $val)
            {
                $group_access[] = ['uid' => $uid,'group_id'=> $val];
            }
            //事务
            Db::transaction(function() use($uid,$data,$group_access){
                //修改
                Db::name('admin')->where(array('id' => $uid))->update($data);
                //删除已存在的用户关联用户组
                Db::name('auth_group_access')->where(['uid' => $uid])->delete();
                //添加更新后的用户组
                Db::name('auth_group_access')->insertAll($group_access);
            });
            return ['url'=>'/admin/User/userList','code'=>0,'msg'=>'修改成功'];
        }
        $uid = input('param.uid');
        $info = Db::name('admin a')
            ->field('a.id,a.username,a.status,GROUP_CONCAT(ag.group_id) gid')
            ->join('auth_group_access ag','a.id = ag.uid')
            ->where(array('a.id' => $uid ))
            ->group('a.id')
            ->find();
        $group_list = Db::name('auth_group')->where('status = :status',['status' => 1])->select();
        $this->assign('info',$info);
        $this->assign('group_list',$group_list);
        return view();
    }

    //删除用户
    public function userDel($uid)
    {
        if(Db::name('admin')->where(['id' => $uid])->delete())
        {
            if(Db::name('auth_group_access')->where(['uid' => $uid])->delete())
            {
                return ['url'=>'/admin/User/userList','code'=>0,'msg'=>'删除成功'];
            }
        }
    }
}