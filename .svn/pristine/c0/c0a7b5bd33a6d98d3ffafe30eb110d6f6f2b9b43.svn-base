<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class BaseinfoController extends CommonController
{
    //课程----------------------------------------------
    public function courseList(){
        $this->display();
    }
    public function addCourse(){
        $this->display();
    }
    public function bindCourse()
    {
        $data = I('get.');
        $courseList = D('Common/Course')->courseList();
        $this->assign('courseList', $courseList['value']['data']);
        $this->assign('data', $data);
        $this->display();
    }
    /**
     * 添加实验
     */
    public function addExperiment(){
        $course_id = I('course_id');
        $this->assign('course_id',$course_id);
        $this->display();
    }
    //年级------------------------------------------------------
//    public function gradeList(){
//        $this->display();
//    }
//    public function addGrade(){
//        $this->display();
//    }
    //专业-------------------------------------------------------
    public function majorList(){
        $this->display();
    }
    public function addMajor(){
        $data = D('Common/Faculty')->getAllFacultyName();
        $this->assign('data',$data);
        $this->display();
    }
    //班级-----------------------------------------------------------
    public function classList(){
        $this->display();
    }
    public function addClass(){
        $Grade_data = D('Common/Grade')->getAllGradeName();
        $Faculty_data = D('Common/Faculty')->getAllFacultyName();
        $this->assign('faculty_data',$Faculty_data);
        $this->assign('grade_data',$Grade_data);
        $this->display();
    }
    public function bindClass()
    {
        $data = I('get.');
        $classList = D('Common/Class')->classList();
        $this->assign('classList', $classList['value']['data']);
        $this->assign('data', $data);
        //print_r($classList['value']['data']);
        $this->display();
    }
    //院系-----------------------------------------------------
    public function facultyList(){
        $this->display();
    }
    public function addFaculty(){
        $this->display();
    }
}