<?php

namespace Api\Controller;

use Common\Controller\CommonController;

class PowerController extends CommonController
{
    /**
     * 获取权限列表
     *
     * @author varro
     */
    public function getPowerList()
    {
        $data = I('get.');
        $powerList = D('Common/Power')->getPowerList($data);

        $this->returnWebData['data'] = $powerList['data'];
        $this->returnWebData['count'] = $powerList['count'];
        $this->ajaxReturn($this->returnWebData);
    }

    public function getDataList(){
        if(I('key')){
            $name = I('key');
            $data = D('Common/Power')->getMsgById($name['id']);
            if ($data['status'] == 1){
                return $this->ajaxReturn($data['value']);
            }else {
                return $this->ajaxReturn($data);
            }
            exit();
        }else {
            $page = I('page', 1, 'intval');
            $limit = I('limit', 10, 'intval');
            $data = D('Common/Power')->powerList();
            if ($data){
                return $this->ajaxReturn($data['value']);
            }else {
                return $this->ajaxReturn(array('status'=>-1, 'code'=>'error', 'value'=>'', 'msg'=>'error'));
            }
        }
    }
    public function addData(){
        $power_name = I("power_name");
        $menu_id = I('menu_id');
        $menu_id = implode(",", $menu_id);
        $menu_id = explode(",", $menu_id);
        $menu_id = array_unique($menu_id);
        $menu_id = implode(",", $menu_id);
        $data = array(
            'power_name' => $power_name,
            'menu_id'  => $menu_id
        );
        if(I("get.edit")){
            $id = 'power_id = '.I("power_id");;
            $re = D('Common/Power')->updateList($id,$data);
        }else {
            $re = D('Common/Power')->addMssage($data);
        }
        
        if($re['status'] == 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
    public function updateMsg(){
        if(I("post.")){
            $id = 'power_id = '.I("power_id");
            $data[I("field")] = I("value");
            $re = D('Common/Power')->updateList($id,$data);
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