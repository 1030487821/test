<?php
namespace Admin\Controller;

class IndexController extends CommonController{
    public function index(){
        $admin_id = session('ADMIN_ID');

        $manage = M('Manage')->find($admin_id);
        $role_id = $manage['role_id'];
        $role = M('Role')->find($role_id);
        $ids = $role['auth_ids'];
        if($admin_id == 1){
            $authsP = M('Auth')->where(array('level'=>0))->select();
            $authsC = M('Auth')->where(array('level'=>1))->select();
        }else{
            $authsP = M('Auth')->where(array('level'=>0,'id'=>array('in',$ids)))->select();
            $authsC = M('Auth')->where(array('level'=>1,'id'=>array('in',$ids)))->select();
        }
        $this->assign('authsP',$authsP);
        $this->assign('authsC',$authsC);
        $this->display();
    }
    public function indexMain(){
        $mysql=M()->query('select version() as version');
        $mysql=$mysql[0]['version'];
        $mysql=empty($mysql)?'未知':$mysql;
        //系统信息
        $info=array(
            'OPERATING_SYSTEM'=>PHP_OS,//操作系统
            'OPERATING_ENVIRONMENT'=>$_SERVER["SERVER_SOFTWARE"],//运行环境
            'PHP_VERSION'=>PHP_VERSION,//PHP版本
            'MYSQL_VERSION'=>$mysql,//MYSQL版本
            'UPLOAD_MAX_FILESIZE'=>ini_get('upload_max_filesize'),//上传附件限制
            'MAX_EXECUTION_TIME'=>ini_get('max_execution_time') . "s"//执行时间限制
        );
        $this->assign('info',$info);
        $this->display();
    }
}