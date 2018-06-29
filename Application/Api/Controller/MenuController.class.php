<?php
namespace Api\Controller;
use Common\Controller\CommonController;
use Qian\AliDaYu;
use Ku\Utils;
/**
 * 菜单导航控制器
 */
class MenuController extends CommonController{
    /**
     * 添加菜单
     */
    public function addMenu(){
        $menuName = I("menu_name");
        $pid = I('pid');
        $icon_url = I("icon_url");
        $sort = I("sort");
        $action = I("action");
        $url = I('url');
        if(I('is_show')){
            $is_show = 1;
        }else {
            $is_show = 0;
        }
        $data = array(
            'menu_name' => $menuName,
            'icon_url'  => $icon_url,
            'pid'       => $pid,
            'action'    => $action,
            'url'       => $url,
            'sort'      => $sort,
            'is_show'   => $is_show
        );
        $re = D('Common/Menu')->addMenu($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
    public function menuList(){
        if(I('key')){
            $name = I('key');
            $data = D('Common/Menu')->getMenuById($name['id']);
            if ($data['status'] == 1){
                return $this->ajaxReturn($data['value']);
            }else {
                return $this->ajaxReturn($data);
            }
            exit();
        }else {
            $page = I('page', 1, 'intval');
            $limit = I('limit', 10, 'intval');
            $data = D('Common/Menu')->getMenuPageList($page,$limit);
            if ($data){
                return $this->ajaxReturn($data);
            }
        }
    }
    public function updateMsg(){
        if(I("post.")){
            $id = 'menu_id = '.I("menu_id");
            $data[I("field")] = I("value");
            $re = D('Common/Menu')->updateMenu($id,$data);
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