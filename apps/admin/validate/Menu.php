<?php

namespace app\admin\validate;

use think\Validate;
/**
登录验证
 */
class Menu extends Validate
{
    protected $rule = [
        'name|名称'    => 'require',
		'url|路径'	=>	'require',
    ];
	protected $scene  = [
        'menuEdit'  =>  ['title','url'],
    ];
}