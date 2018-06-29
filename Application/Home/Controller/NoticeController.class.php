<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class NoticeController extends CommonController
{
    public function noticeList(){
        $this->display();
    }
    public function myNotice(){
        $data = D('Common/Notice')->getNoticeList();
        $this->assign('data', $data);
        $this->display();
    }
    public function addNotice(){
        $this->display();
    }
}