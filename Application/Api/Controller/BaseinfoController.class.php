<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/14
 * Time: 10:49
 */

namespace Api\Controller;

use Common\Controller\CommonController;

class BaseinfoController extends CommonController
{
    /**
     * 获取所有年级
     *
     * @author varro
     */
    public function getGradeList()
    {
        $data = I('request.');
        $data['page'] = $data['page'] ?: 1;
        $gradeInfo = D('Common/Grade')->getGradeList($data);
        $this->returnWebData['data'] = $gradeInfo;
        $this->returnWebData['count'] = count($gradeInfo);
        $this->ajaxReturn($this->returnWebData);
    }

    /**
     * 添加年级
     *
     * @author varro
     */
    public function addGrade()
    {
        try {
            $data = I('request.');
            $data['user_id'] = session('user_data')['user_id'] ?? $data['user_id'];

            if (empty($data['user_id'])) {
                $this->Error('user_id为空');
            }

            if (empty($data['grade_name'])) {
                $this->Error('grade_name为空');
            }

            $userInfo = D('Common/User')->getUserInfo(['user_id' => $data['user_id']]);
            if (empty($userInfo)) {
                $this->Error('非法操作,用户不存在');
            }

            if (!in_array($userInfo['role_id'], [1, 2])) {
                $this->Error('非法操作, role_id => ' . $userInfo['role_id']);
            }

            $gradeInfo = D('Common/Grade')->getGradeList(['grade_name' => $data['grade_name']]);
            if (!empty($gradeInfo)) {
                $this->Error('该年级已存在');
            }

            if (!D('Common/Grade')->add(['grade_name' => $data['grade_name'], 'status' => 1])) {
                $this->Error('添加年级失败');
            }

            $this->returnData['msg'] = '添加年级成功';
        } catch (\Exception $e) {
            $this->returnError($e);
        } finally {
            $this->ajaxReturn($this->returnData);
        }
    }


//院系-------------------------------------------------------------------
    /**
     * 院系列表
     */
    public function getFacultyList(){
        $page = I('page', 1, 'intval');
        $limit = I('limit', 10, 'intval');
        $data = D('Common/Faculty')->facultyList();
        if ($data){
            return $this->ajaxReturn($data['value']);
        }else {
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'error', 'value'=>'', 'msg'=>'error'));
        }
    }
    /**
     * 添加院系
     */
    public function addFaculty(){
        $facultyName = I("faculty_name");
        $data = array(
            'faculty_name' => $facultyName,
            'create_time'  => date('Y-m-d H:i:s',time()),
            'update_time'  => date('Y-m-d H:i:s',time())
        );

        $re = D('Common/Faculty')->addFaculty($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
//专业-------------------------------------------------------------------
    /**
     * 专业列表
     */
    public function getMajorList(){
        $page = I('page', 1, 'intval');
        $limit = I('limit', 10, 'intval');
        $data = D('Common/Major')->majorList();
        if ($data){
            return $this->ajaxReturn($data['value']);
        }else {
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'error', 'value'=>'', 'msg'=>'error'));
        }
    }
    /**
     * 添加专业
     */
    public function addMajor(){
        $majorName = I("major_name");
        $facultyId = I("faculty_id");
        $data = array(
            'major_name' => $majorName,
            'faculty_id' => $facultyId,
            'create_time'  => date('Y-m-d H:i:s',time()),
            'update_time'  => date('Y-m-d H:i:s',time())
        );
        $re = D('Common/Major')->addMajor($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }

    /**
     * ajax获取专业名称
     */
    public function ajaxGetMajorName(){
        $facultyId = I("pid");
        $re = D('Common/Major')->getAllMajorName($facultyId);
        if ($re){
            return $this->ajaxReturn($re);
        }else {
            return $this->ajaxReturn('');
        }
    }
//班级-------------------------------------------------------------------
    /**
     * 班级列表
     */
    public function getClassList(){
        $page = I('page', 1, 'intval');
        $limit = I('limit', 10, 'intval');
        $data = D('Common/Class')->classList();
        if ($data){
            return $this->ajaxReturn($data['value']);
        }else {
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'error', 'value'=>'', 'msg'=>'error'));
        }
    }

    /**
     * 添加班级
     */
    public function addClass(){
        $class_name = I("class_name");
        $major_id = I("major_id");
        $grade_id = I("grade_id");
        $data = array(
            'class_name' => $class_name,
            'major_id' => $major_id,
            'grade_id' => $grade_id,
            'create_time'  => date('Y-m-d H:i:s',time()),
            'update_time'  => date('Y-m-d H:i:s',time())
        );

        $re = D('Common/Class')->addClass($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
    //课程-------------------------------------------------------------------
    /**
     * 课程列表
     */
    public function getCourseList(){
        $page = I('page', 1, 'intval');
        $limit = I('limit', 10, 'intval');
        $data = D('Common/Course')->courseList();
        if ($data){
            return $this->ajaxReturn($data['value']);
        }else {
            return $this->ajaxReturn(array('status'=>-1, 'code'=>'error', 'value'=>'', 'msg'=>'error'));
        }
    }

    /**
     * 添加课程
     */
    public function addCourse(){
        $class_name = I("class_name");
        $major_id = I("major_id");
        $grade_id = I("grade_id");
        $data = array(
            'class_name' => $class_name,
            'major_id' => $major_id,
            'grade_id' => $grade_id,
            'create_time'  => date('Y-m-d H:i:s',time()),
            'update_time'  => date('Y-m-d H:i:s',time())
        );

        $re = D('Common/Course')->addCourse($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }
    /**
     * 添加课程
     */
    public function addExperiment(){
        $data = I("post.");
        $re = D('Common/Experiment')->addExperiment($data);
        if($re['status'] != 1){
            return $this->ajaxReturn($re);
        }
        return $this->ajaxReturn(array('status'=>1, 'code'=>'success', 'value'=>'', 'msg'=>'添加成功'));
    }



    /**
     * ajax获取实验名称
     */
    public function ajaxGetExperimentName(){
        $CourseId = I("pid");
        $re = D('Common/Experiment')->getAllExperimentName($CourseId);
        if ($re){
            return $this->ajaxReturn($re);
        }else {
            return $this->ajaxReturn('');
        }
    }
}