<?php
namespace Common\Model;

use Think\Model;

class CourseModel extends Model
{
    protected $tableName = 'course';
    protected $pk = 'course_id';

    public function getCourseInfo($courseId)
    {
        return $this->where("course_id = $courseId")->find();
    }
    /**
     * 获取所有课程的名称
     */
    public function getAllCourseName(){
        return $this->where('status=1')->field('course_id,course_name')->select();
    }

    /**
     * 获取课程列表
     * @return array
     */
    public function courseList(){
        $data = $this->select();
        if($data){
            $count = count($data);
            $value = $this->getTableList($count, $data);
            if ($value){
                $re = array('status'=>1,'code'=>'success', 'value'=>$value, 'msg'=>'ok');
            }else {
                $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
            }
        }else {
            $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
        }
        return $re;
    }


    private function getTableList($count,$data){
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = '';
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }
}