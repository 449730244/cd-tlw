<?php
/**
 * Created by PhpStorm.
 * User: aliez
 * Date: 13/11/2017
 * Time: 17:43
 */
namespace app\admin\controller;

use think\Db;

class System extends Common
{
   public function _initialize()
   {
       return parent::_initialize(); // TODO: Change the autogenerated stub
   }

   public function webSet()
   {
       if(request()->isPost())
       {
           $file = request()->file('logo');
           if($file){
               $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
               if($info){
                   $logo = '/public'.DS.'uploads/'.$info->getSaveName();
               }else{
                   // 上传失败获取错误信息
                   return ['code'=>0,'msg' => $file->getError()];
               }
           }
           $data = input('post.');
           $data['logo'] = $logo;
           if($data['id'])
           {
               $res = Db::name('web_info')->where(['id' => $data['id']])->save($data);
           }else{
               $res = Db::name('web_info')->insert($data);
           }
           if($res)
           {
               return ['code' => 1,'msg' => '网站信息设置成功'];
           }else{
               return ['code' => 0,'msg' => '网站信息设置失败'];
           }
       }
       $info  =  Db::name('web_info')->find();
       $this->assign('info',$info);
       return view();
   }

}