<?php
namespace Admin\Controller;
class CodeController extends CommonController{
    public function lists(){
        $id = I('get.cid',0,'intval');
        //显示name 公司名称
        $com_name = M('Users')->field('id,com_name')->limit(1)->find($id);
        //分页
        $base = M('Base');
        $count = $base->where(array('cid'=>$id))->count();
        $pageSize = 15;
        $page = new \Think\Page($count,$pageSize);
        $show = $page->show();
        //查询数据
        $bases = $base->alias('b')
            ->field('b.*,u.com_name')
            ->join('__USERS__ u on u.id=b.cid')
            ->where(array('b.cid'=>$id))
            ->order('b.id desc')
            ->limit($page->firstRow,$page->listRows)
            ->select();
        $this->assign('name',$com_name['com_name']);
        $this->assign('bases',$bases);
        $this->assign('show',$show);
        $this->assign('id',$id);
        $this->display();
    }
    public function add(){
        $id = I('get.id');//user表id
        if(IS_POST){
            $baseData = D('Base');
            if(!$baseData->create()){
                $this->error($baseData->getError());
            }else{
                $baseData->cid = $id;
                //获取当前公司的记录数
                $count = M('Base')->where(array('cid'=>$id))->count();
                $user = M('Users')->field('max_num')->where(array('id'=>$id))->find();
                if($count >= $user['max_num']){
                    $this->error('人数超过'.$user['max_num'].',请联系管理员');
                }
                $rs = $baseData->add();
                if($rs){
                    $this->success('添加成功',U('lists',array('cid'=>$id)));
                }else{
                    $this->error('添加失败');
                }
            }
        }else{
            $user = D('Users')->field('id,com_name')->find($id);
            $this->assign('user',$user);
            $this->display();
        }
    }
    public function modify(){
        $id = I('get.id');
        $cid = I('get.cid');
        $baseModel = D('Base');
        if(IS_POST){
            if(!$baseModel->create()){
                $this->error($baseModel->getError());
            }else{
                $rs = $baseModel->where(array('id'=>$id))->save();
                if($rs){
                    $this->success('修改成功',U('lists',array('cid'=>$cid)));
                }else{
                    $this->error('修改失败');
                }
            }
        }else{
            $user = D('Users')->field('id,com_name')->find($cid);
            $base = $baseModel->find($id);
            $this->assign('user',$user);
            $this->assign('base',$base);
            $this->display();
        }
    }
    /*public function del(){
        $id = I('get.id');//删除一个的id
        $ids = I('get.ids');//多选删除的id
        $article = M('Base');
        if($id){
            $where = array('id'=>$id);
        }elseif ($ids){
            $where = array('id'=>array('in',$ids));
        }
        $rs = $article->where($where)->data(array('isdelete'=>1))->save();
        if($rs){
            $this->ajaxReturn(array('status'=>1,'msg'=>'删除成功'));
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'删除失败'));
        }
    }*/
    //ajax更新
    /*public function mySort(){
        if(IS_AJAX){
            $id=I('post.id');
            $sort=I('post.sort');
            $rs=M('Base')->where(array('id'=>$id))->data(array('sort'=>$sort))->save();
            if($rs){
                $this->ajaxReturn(array('status'=>1,'msg'=>'操作成功'));
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>'操作失败'));
            }
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'非法操作'));
        }
    }*/
    public function qrcode()
    {
        if(IS_AJAX){
            header("Content-type: text/html; charset=utf-8");
            $name=$_POST["name"];
            $title=$_POST["title"];
            $adr=$_POST["adr"];
            $org=$_POST["org"];
            $cell=$_POST["cell"];
            $home=$_POST["home"];
            $url=$_POST["url"];
            $email=$_POST["email"];
            $id=$_POST["id"];
            $txt="BEGIN:VCARD\r\n"
                ."N:".$name."\r\n
                ORG:".$org."\r\n
                TITLE:".$title."\r\n
                ADR;TYPE=WORK:;;".$adr."\r\n
                TEL;TYPE=CELL,VOICE:".$cell."\r\n
                TEL;TYPE=WORK,VOICE:".$home."\r\n
                EMAIL;TYPE=PREF,INTERNET:".$email."\r\n
                END:VCARD";
            file_put_contents('Uploads/'.$id.'_index.vcf',$txt);
        }else{
            $id = I('get.id');
            $base = M('Base')->find($id);
            if($base['isdelete'] == 1){
                redirect('/0');
            }
            $menu = M('Menu')->field('name')->find($base['cid']);
            $this->assign('name',$menu['name']);
            $this->assign('base',$base);
            $this->assign('id',$id);
            $this->display();
        }

    }
    /*public function doCode(){
        Vendor('phpqrcode.phpqrcode');
        //引入phpqrcode库文件
        //$data = '/admin.php/Admin/Code/qrcode/id/33.html';
        $data = 'https://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%90%8D%E7%89%87%E5%9B%BE%E7%89%87&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&cs=1009429076,33805773&os=3178303933,3407720435&sicid=3228971653,3897525554&pn=170&rn=1&di=53892098150&ln=1988&fr=&fmq=1498542025780_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=78&hs=2&objurl=http%3A%2F%2Fimg3.redocn.com%2Ftupian%2F20150409%2Frongguangwenyindianmingpianmobanshiliangwenjian_4113108.jpg&rpstart=0&rpnum=0&adpicid=0';
        $filename = 'Uploads/headpic/'.time().'.png';
        // 纠错级别：L、M、Q、H
        $errorCorrectionLevel = 'L';
        // 点的大小：1到10
        $matrixPointSize = 4;
        //创建一个二维码文件
        \QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        //输入二维码到浏览器
        \QRcode::png($data);
    }*/
    public function changeDel(){
        $isDelete = I('post.isdelete');
        $id = I('post.id');
        if($isDelete == 0){
            $rs = M('Base')->where(array('id'=>$id))->data(array('isdelete'=>1))->save();
        }
        if($isDelete == 1){
            $rs = M('Base')->where(array('id'=>$id))->data(array('isdelete'=>0))->save();
        }
        if($rs){
            $this->ajaxReturn(array('status'=>1,'msg'=>'修改成功','isdel'=>$isDelete));
        }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'修改失败'));
        }

    }
}