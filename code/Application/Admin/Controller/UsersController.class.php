<?php
namespace Admin\Controller;

class UsersController extends CommonController {

    // 管理员列表
    public function lists(){
        $where = array("user_type"=>1);
        //分页
        $totalRow = M('Role')->count();
        $pageSize = 20;
        $Page = new \Think\Page($totalRow,$pageSize);
        $show = $Page->show();
        $userList = M('Users')
            ->where($where)
            ->order("create_time DESC")
            ->limit($Page->firstRow, $Page->listRows)
            ->order('id DESC')
            ->select();
        $this->assign("userList",$userList);
        $this->display();
    }

    // 管理员添加
    public function add(){
        if(IS_POST){
            $user = D('Users');
            if (!$user->create()) {
                $this->error($user->getError());
            } else {
                $user->pass = I('post.user_pass');
                $rs = $user->add();
                if ($rs) {
                    $this->success("添加成功！", U("Users/lists"));
                } else {
                    $this->error("添加失败！");
                }
            }
        }else{
            $this->display();
        }
    }

    // 管理员编辑
    public function modify(){
        $id = I('get.id',0,'intval');
        if(!$id){
            $id = session('ADMIN_ID');
        }
        if (IS_POST ) {
            $user = D('Users');
            if (!$user->create($_POST,2)) {
                $this->error($user->getError());
            } else {
                $user->pass = I('post.user_pass');
                $rs = $user->where(array('id'=>$id))->save();
                if ($rs) {
                    $this->success("修改成功！");
                } else {
                    $this->error("修改失败！");
                }
            }
        }else{
            $userInfo = D('Users')->where(array("id"=>$id))->find();
            $this->assign('userInfo',$userInfo);
            $this->display();
        }

    }

    // 管理员删除
    public function del(){
        $id = I('get.id',0,'intval');

        if($id==1){
            $this->error("最高管理员不能删除！");
        }
        $rs = M('Users')->delete($id);
        if ($rs) {
            M("RoleUser")->where(array("user_id"=>$id))->delete();
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function maxNum(){
        if(IS_AJAX){
            $id = I('post.id');
            $max_num = I('post.max_num');
            $rs=M('Users')->where(array('id'=>$id))->data(array('max_num'=>$max_num))->save();
            if($rs){
                $this->ajaxReturn(array('status'=>1,'msg'=>'操作成功'));
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>'操作失败'));
            }
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'非法操作'));
        }
    }
}


