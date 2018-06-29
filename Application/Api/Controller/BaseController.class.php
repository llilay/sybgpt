<?php
namespace Api\Controller;
use Common\Controller\CommonController;

class BaseController extends CommonController{
    /**
     * 自动检测登录状态
     */
/*    public function _initialize() {
        if(!$this->userOnline()){
            return $this->ajaxReturn(array('status'=>-1,'code'=>'noLogin','value'=>'','msg'=>'尚未登录'));
        }
    }*/
}