<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/23
 * Time: 15:39
 */

namespace Common\Model;

use Think\Model;

class ClassModel extends Model
{
    protected $tableName = 'class';
    protected $pk = 'class_id';

    /**
     * 添加班级
     * @param $data
     * @return array
     */
    public function addClass($data){
        if(!$this->create($data,4)){
            $re = array('status'=>-1, 'code'=>'paramsErr', 'value'=>'', 'msg'=>$this->getError());
        }else{
            $res = $this->add($data);
            if($res){
                $re = array('status'=>1, 'code'=>'success', 'value'=>$res, 'msg'=>'ok');
            }else{
                $re = array('status'=>-1,'code'=>'serverErr', 'value'=>'', 'msg'=>'服务器繁忙');
            }
        }
        return $re;
    }


    /**
     * 获取班级列表
     * @return array
     */
    public function classList(){
        $data = $this
            ->join('LEFT JOIN major ON class.major_id = major.major_id' )
            ->join(     'LEFT JOIN faculty ON major.faculty_id = faculty.faculty_id' )
            ->join(     'LEFT JOIN grade ON class.grade_id = grade.grade_id' )
            ->field('class.*,major.major_name,faculty.faculty_name,grade.grade_name')
            ->select();
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

    /**
     * 获取所有班级的名称
     */
    public function getAllClassName(){
        return $this->where('status=1')->field('class_id,class_name')->select();
    }

    private function getTableList($count,$data){
        $temp = array();
        $temp['code'] = 0;
        $temp['msg'] = '';
        $temp['count'] = (int)$count;
        $temp['data'] = $data;
        return $temp;
    }

    public function getClass($classId)
    {
        return $this->where("class_id = '$classId'")->find();
    }
}