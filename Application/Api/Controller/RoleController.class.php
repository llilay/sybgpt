<?php

namespace Api\Controller;

use Common\Controller\CommonController;

class RoleController extends CommonController
{

    /**
     * 获取角色列表
     *
     * @author varro
     */
    public function getRoleList()
    {
        $data = I('post.');
        $data['page'] = $data['page'] ?: 1;
        $roleList = D('Common/Role')->getRoleList($data);
        $this->returnWebData['data'] = $roleList['data'];
        $this->returnWebData['count'] = $roleList['count'];
        $this->ajaxReturn($this->returnWebData);
    }

    public function addRole(){
        $role_name = I("role_name");
        $power_id = I('power_id');
        $power_id = implode(",",$power_id);
        $data = array(
            'role_name' => $role_name,
            'power_id'  => $power_id
        );
        if(I("get.edit")){
            $id = 'role_id = '.I("role_id");;
            $re = D('Common/Role')->updateRole($id,$data);
        }else {
            $re = D('Common/Role')->addRole($data);
        }
        if($re['status'] == 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
    public function updateMsg(){
        if(I("post.")){
            $id = 'role_id = '.I("role_id");
            $data[I("field")] = I("value");
            $re = D('Common/Role')->updateRole($id,$data);
            if($re['status'] == 1){
                return $this->ajaxReturn($re);
            }else {
                return $this->ajaxReturn(array('status'=>-1, 'code'=>'filed', 'value'=>'', 'msg'=>'修改失败'));
            }
        }else {
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'filed', 'value'=>'', 'msg'=>'修改失败'));
        }
    }
}