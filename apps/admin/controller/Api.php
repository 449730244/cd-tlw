<?php
/**
 * Created by PhpStorm.
 * User: aliez
 * Date: 28/11/2017
 * Time: 17:19
 */
namespace app\admin\controller;

use think\Controller;

class Api extends Controller
{
    public function listApi()
    {
        $data = [
            'code' => 200,
            'list' => [
                ['name' => 'zhangsan','age' => '20'],
                ['name' => 'lisi','age' => '18'],
                ['name' => 'wangwu','age' => '19']
            ]
        ];
        return json($data);
    }
    public function fileUpload()
    {
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $path = 'http://www.cd-tlw.com/public'.DS.'uploads/'.$info->getSaveName();
                $data = [
                    'code'    => 200,
                    'imgPath' => $path
                ];
                return json($data);
            }else{
                // 上传失败获取错误信息
                return json(['code'=>201,'message' => $file->getError()]);
            }
        }
    }
}