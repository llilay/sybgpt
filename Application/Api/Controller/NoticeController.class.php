<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/5/5
 * Time: 15:43
 */

namespace Api\Controller;

use Common\Controller\CommonController;

class NoticeController extends CommonController
{
    /**
     * 获取通知列表
     *
     * @author varro
     */
    public function getNoticeList()
    {
        $data = I('request.');
        $data['page'] = $data['page'] ?: 1;
        $noticeData = D('Common/Notice')->getNoticeList($data);
        $this->returnWebData['data'] = $noticeData;
        $this->returnWebData['count'] = count($noticeData);
        $this->ajaxReturn($this->returnWebData);
    }

    /**
     * 添加通告
     *
     * @author varro
     */
    public function addNotice()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (!isset($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (!isset($data['notice_title'])) {
                $this->Error('notice_title为空');
            }

            if (!isset($data['notice_content'])) {
                $this->Error('notice_content为空');
            }

            if (mb_strlen($data['notice_title']) > 100) {
                $this->Error('notice_title不可多于100个字符');
            }

            if (mb_strlen($data['notice_content']) > 255) {
                $this->Error('notice_content不可多于255个字符');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (!$userInfo) {
                $this->Error('该用户不存在, user_id => ' . $data['user_id']);
            }

            if (!in_array($userInfo['role_id'], [1, 2])) {
                $this->Error('无权限执行该操作, role_id => ' . $userInfo['role_id']);
            }

            $time = time();
            $params = [
                'notice_title' => $data['notice_title'],
                'notice_content' => $data['notice_content'],
                'create_time' => $time,
                'update_time' => $time,
                'publish_time' => $time,
                'end_time' => $time
            ];

            if (!D('Common/Notice')->addNotice($params)) {
                $this->Error('添加失败');
            }

            $this->returnData['msg'] = '添加成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }
}
