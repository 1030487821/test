<?php
namespace Admin\Model;
use Think\Model;
class UsersModel extends Model{
    public $_validate = array(
        array('user_login','require','用户名不能为空',0,'',1),
        //array('user_login','/^[a-zA-Z0-9]\w{5,19}$/','用户名以字母下划线开头,由字母数字下划线组成，6-20位',0,'',1),
        //array('user_login',array('admin','administrator'),'用户名不能是超级管理员',0,'notin',1),
        array('user_login','','用户名已占用',0,'unique',1),
        array('user_pass','require','密码不能为空',0,'',1),
        //array('user_pass','/^[a-zA-Z0-9]{6,20}$/','密码由字母数字下划线组成，6-20位',0,'',1),
        //array('user_email','require','邮箱不能为空！'),
        //array('user_email','','邮箱帐号已经存在！',0,'unique',1),
        //array('user_email','email','邮箱格式不正确！'),
        array('com_name','require','公司名称不能为空')
    );
    public $_auto = array(
        array('user_pass','md5',1,'function'),
        array('user_pass','setPassword',2,'callback'),
        array('user_pass','',2,'ignore'),
        array('create_time',NOW_TIME,1)
    );
    public function setPassword($password){
        return $password?md5($password):'';
    }
}