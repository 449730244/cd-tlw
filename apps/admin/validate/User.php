<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 10:14
 */

namespace app\admin\validate;

use think\Validate;
/**
登录验证
 */
class User extends Validate
{
    protected $rule = [
        'username|用户名'    => 'require',
        'password|密码'      => 'require'
    ];
}