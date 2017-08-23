<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {
    public function login(){
        if(IS_AJAX){
            $username = I('post.username');
            $password = md5(I('post.password'));
            $yzmcode = I('post.yzmcode');
            $verify = new \Think\Verify();
            $rs = $verify->check($yzmcode,1);
            if(!$rs){
                $this->ajaxReturn(array('status'=>0,'msg'=>'验证码输入错误'));
            }
            $userInfo = M('Manage')->where(array('name'=>$username))->find();
            if($userInfo['name']!=$username){
                $this->ajaxReturn(array('status'=>0,'msg'=>'用户名不存在'));
            }
            if($userInfo['pwd']!=$password){
                $this->ajaxReturn(array('status'=>0,'msg'=>'密码不正确'));
            }


            session('userInfo',$userInfo['name']);//讲username写进session
            session('ADMIN_ID',$userInfo['id']);//写进session
            session('com_name',$userInfo['com_name']);
            $this->ajaxReturn(array('status'=>1,'msg'=>'登陆成功'));
        }else{
            //判断用户是否已登录 若登陆 跳转到主页
            if(session('userInfo')){
                $this->redirect('Index/index');
            }
            $this->display();
        }
    }
    public function loginOut(){
        session('[destroy]');
        $this->redirect('login');
    }
    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }
}