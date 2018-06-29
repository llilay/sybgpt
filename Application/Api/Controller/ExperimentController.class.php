<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/5/10
 * Time: 12:21
 */

namespace Api\Controller;

use Common\Controller\CommonController;

class ExperimentController extends CommonController
{
    /**
     * 获取实验列表
     *
     * @author varro
     */
    public function getExperimentList()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                exit($this->msgFormat('该用户不存在, user_id => ' . $data['user_id']));
            }

            if (in_array($userInfo['role_id'], [6])) {
                $experimentData = D('Common/Experiment')->getExperimentListByStudent($userInfo['student_id']);
            } else {
                $experimentData = D('Common/Experiment')->getExperimentList();
            }

            $this->returnWebData['data'] = $experimentData;
            $this->returnWebData['count'] = count($experimentData) ?: 0;
            $this->returnWebData['msg'] = '获取实验列表成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnWebData);
        }
    }
}
