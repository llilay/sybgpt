<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class PowerController extends CommonController
{
    public function menuList(){
        $this->display();
    }
    public function addMenu(){
        $data = D('Common/Menu')->getAllMenuName();
        $this->assign('data',$data);
        $this->display();
    }
    //权限-----------------------------------------------
    public function powerList(){
        $this->display();
    }
    public function addPower(){
        $data = D('Common/Menu')->getNewMenuArr();
        
        if(I('get.edit')==1){
            $power_id = I('get.power_id');
            $power_name = I('get.power_name');
            $menu_id  = I('get.menu_id');
            $menu_id = explode(',', $menu_id);
            
            if(!empty($menu_id)){
                foreach ($data as $k=>$v){
                    if(array_search($v['menu_id'],$menu_id) === false){
            
                    }else {
                        $data[$k]['checked'] = 1;
                    }
                    if ($v['children']){
                        foreach ($v['children'] as $kk=>$vv){
                            if(array_search($vv['menu_id'],$menu_id) === false){
                            
                            }else {
                                $data[$k]['children'][$kk]['checked'] = 1;
                                //var_dump($data[$k]['children']);
                            }
                        }
                    }
                }
            }
            $power = array(
                'power_id'   => $power_id,
                'power_name' => $power_name
            );
            $this->assign('edit',1);
            $this->assign('data',$data);
            $this->assign('power',$power);
        }else {
            $this->assign('edit',0);
            $this->assign('data',$data);
        }
        $this->display();
    }
    //角色--------------------------------------------------
    public function roleList(){
        $this->display();
    }
    public function addRole(){
        $data = D('Common/Power')->getPowerList();
        $data = $data['data'];

        if(I('get.edit')==1){
            $role_id = I('get.role_id');
            $role_name = I('get.role_name');
            $power_id  = I('get.power_id');
            $power_id = explode(',', $power_id);
            $role = array(
                'role_id'   => $role_id,
                'role_name' => $role_name
            );
            if(!empty($power_id)){
                foreach ($data as $k=>$v){
                    if(array_search($v['power_id'],$power_id) === false){
                        
                    }else {
                        $data[$k]['checked'] = 1;
                    }
                }
            }

            $this->assign('data',$data);
            $this->assign('edit',1);
            $this->assign('role',$role);
        }else {
            //var_dump($data);
            $this->assign('edit',0);
            $this->assign('data',$data);
        }
        $this->display();
    }
    public function editRole(){
        $this->display();
    }
    //用户--------------------------------------------------
    public function userList(){
        $this->display();
    }
    public function addUser(){
        $role = D('Common/Role')->getRoleList();
        $this->assign('role', $role['data']);
        //echo md5("@!".sha1(123456)."@!");密码
        $this->display();
    }
    
}