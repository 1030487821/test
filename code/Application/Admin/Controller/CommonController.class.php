<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        //判断是否登录 用于所有页面
        if(!session('userInfo')){
            $this->redirect('Public/login');
        }
    }
}