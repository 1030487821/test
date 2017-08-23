<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model{
    //自动验证
    protected $_validate = array(
        array('name', 'require', '角色名称不能为空！', 1),
    );

    protected $_auto = array(
        array('create_time',NOW_TIME,1),
        array('update_time',NOW_TIME,2),
    );
    //只调用一种方法实现
    public function getTree($type='Tr',$roleID=0){
        $data = $this->getTreeArr();
        $fun = 'getTree'.$type;
        return $this->$fun($data,$level=0,$roleID);
    }
    public function getTreeArr($pid=0){
        $menuList = M('Menu')->field('id,parentid,name,model,action')->where(array('parentid'=>$pid))->order('id')->select();
        if($menuList){
            foreach ($menuList as &$m){
                $m['child'] = $this->getTreeArr($m['id']);
            }
        }
        return $menuList;
    }
    public function getTreeTr($data,$level=0,$roleID){
        global $str;
        $nbsp = str_repeat('&nbsp;&nbsp;&nbsp;',$level);
        $char = str_repeat('─',$level);
        $char = $char?$nbsp.'└'.$char:'';
        $authList = M('AuthAccess')->where(array("role_id"=>$roleID))->getField("menuid",true);
        if(is_array($data) && !empty($data)){
            $checked = '';
            foreach ($data as $val){
                $checked = '';
                if(in_array($val['id'],$authList)){
                    $checked = 'checked';
                }
                $str.="<tr>\n";
                $str.="<td>{$char}<input type='checkbox' name='menuid[]' $checked value='{$val['id']}' level='$level'
                onclick='checknode(this);'/>{$val['name']}</td>\n";
                $str.="</tr>\n";
                $this->getTreeTr($val['child'],$level+1,$roleID);
            }
        }
        return $str;
    }
   /*public function checked($authList) {
        $menu = M('Menu')->field('id')->select();
        foreach ($menu as $k=>$val){

        }
        $model = $menu['model'];
        $action = $menu['action'];
        $name=strtolower("$model/$action");
        if($authList){
            if (in_array($name, $authList)) {
                return 'checked';
            } else {
                return '';
            }
        }else{
            return '';
        }

    }*/


}