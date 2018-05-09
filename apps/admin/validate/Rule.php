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
class Rule extends Validate
{
    protected $rule = [
        'name|权限名'    => 'require',
        'title|权限'      => 'require'
    ];
}