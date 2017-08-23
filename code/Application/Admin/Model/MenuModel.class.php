<?php
namespace Admin\Model;
use Think\Model;
class MenuModel extends Model{
    public function getTree($selectId){
        $meuns = $this->field('id,name')->limit('1,3')->select();
        if($meuns){
            $option = '';
            foreach ($meuns as $menu){
                $select = '';
                if($menu['id'] == $selectId){
                    $select = 'selected';
                }
                $option .= "<option value='{$menu['id']}' $select>{$menu['name']}</option>\n";
            }
        }
        return $option;
    }
}