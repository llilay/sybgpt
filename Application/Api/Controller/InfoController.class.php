<?php
namespace Api\Controller;
use Ku\Utils;
class InfoController extends BaseController{
    /**
     * 用户个人资料编辑
     */
    public function updateUser(){
        $img_url = I('post.iu');
        if(empty($re)){
            return $this->ajaxReturn(array('status'=>-1,'code'=>'serverErr','value'=>'','msg'=>'操作失败'));
        }else{
            return $this->ajaxReturn(array('status'=>1,'code'=>'success','value'=>'','msg'=>'操作成功'));
        }
    }
    /**
     * 用户旧密码检测
     */
    public function checkPwd(){
        
        $pwd = Utils::pwdMD5($pwd);
        if($pwd == $user['user_pwd']){
            return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>''));
        }else{
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'dataErr', 'value'=>'', 'msg'=>'密码不正确'));
        }
    }
    /**
     * 修改密码
     */
    public function updatePwd(){
        $pwd_old = I('post.po');
        cookie('user_h', null);
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'修改成功'));
    }
    /**
     * 用户头像上传
     * @return unknown
     */
    public function upload(){
        $file = $_FILES['file'];
        $options = array('savePath'=>'Users/headers/');
        $re = D('Common/File')->saveUploadOneFile($file,$options);
        return $this->ajaxReturn($re);
        
    }
}