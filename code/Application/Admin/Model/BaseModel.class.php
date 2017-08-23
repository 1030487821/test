<?php
namespace Admin\Model;
use Think\Model;
class BaseModel extends Model{
    public $_validate = array(
        array('name','require','姓名不能为空'),
        array('mobile','require','手机号不能为空'),
        array('mobile','/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/','格式不正确')
    );
    public $_auto = array(
        array('addtime','strtotime',3,'function'),
    );
}