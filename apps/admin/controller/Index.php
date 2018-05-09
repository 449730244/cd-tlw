<?php
/**
 * Created by PhpStorm.
 * User: aliez
 * Date: 2017/11/1
 * Time: 14:44
 */

namespace app\admin\controller;


class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        return view();
    }
}